<?php

final class DateTimeService
{
    private const DEFAULT_TIMEZONE = 'America/La_Paz';
    private const DEFAULT_OFFSET_MINUTES = -240;

    public static function supportedTimezones(): array
    {
        return [
            'America/La_Paz' => self::offsetMinutesForTimezone('America/La_Paz'),
            'America/Lima' => self::offsetMinutesForTimezone('America/Lima'),
        ];
    }

    public static function defaultTimezoneName(): string
    {
        return self::DEFAULT_TIMEZONE;
    }

    public static function defaultOffsetMinutes(): int
    {
        return self::DEFAULT_OFFSET_MINUTES;
    }

    public static function isValidTimezoneName($timezoneName): bool
    {
        if (!is_string($timezoneName) || trim($timezoneName) === '') {
            return false;
        }

        try {
            new DateTimeZone($timezoneName);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function isSupportedTimezoneName($timezoneName): bool
    {
        return self::isValidTimezoneName($timezoneName);
    }

    public static function offsetMinutesForTimezone(string $timezoneName): int
    {
        $timezone = new DateTimeZone($timezoneName);
        $now = new DateTimeImmutable('now', $timezone);

        return (int) ($timezone->getOffset($now) / 60);
    }

    public static function normalizeTimezoneName($timezoneName): string
    {
        $timezoneName = is_string($timezoneName) ? trim($timezoneName) : '';

        if (self::isValidTimezoneName($timezoneName)) {
            return $timezoneName;
        }

        return self::DEFAULT_TIMEZONE;
    }

    public static function normalizeOffsetMinutes($offsetMinutes, string $timezoneName): int
    {
        return self::offsetMinutesForTimezone(self::normalizeTimezoneName($timezoneName));
    }

    public static function timezoneFromClaims(array $claims): DateTimeZone
    {
        return new DateTimeZone(self::normalizeTimezoneName($claims['timezone_name'] ?? null));
    }

    public static function nowLocal(array $claims): DateTimeImmutable
    {
        return new DateTimeImmutable('now', self::timezoneFromClaims($claims));
    }

    public static function nowUtc(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    public static function formatMysql(DateTimeImmutable $dateTime): string
    {
        return $dateTime->format('Y-m-d H:i:s');
    }

    public static function localMysqlNow(array $claims): string
    {
        return self::formatMysql(self::nowLocal($claims));
    }

    public static function localMysqlDate(array $claims): string
    {
        return self::nowLocal($claims)->format('Y-m-d');
    }

    public static function localMysqlTime(array $claims): string
    {
        return self::nowLocal($claims)->format('H:i:s');
    }

    public static function utcMysqlNow(): string
    {
        return self::formatMysql(self::nowUtc());
    }

    public static function mysqlTimeZoneOffset($offsetMinutes): string
    {
        $offsetMinutes = is_numeric($offsetMinutes) ? (int) $offsetMinutes : self::DEFAULT_OFFSET_MINUTES;
        $sign = $offsetMinutes < 0 ? '-' : '+';
        $absoluteMinutes = abs($offsetMinutes);
        $hours = (int) floor($absoluteMinutes / 60);
        $minutes = $absoluteMinutes % 60;

        return sprintf('%s%02d:%02d', $sign, $hours, $minutes);
    }

    public static function timezoneContext($timezoneName): array
    {
        $timezoneName = self::normalizeTimezoneName($timezoneName);

        return [
            'timezone_name' => $timezoneName,
            'utc_offset_minutos' => self::offsetMinutesForTimezone($timezoneName),
        ];
    }

    public static function timezonesFromDatabase(PDO $conexion): array
    {
        $timezones = [];
        $stmt = $conexion->query("
            SELECT
                idtimeszones,
                timezone_name
            FROM t_timeszones
            WHERE timezone_name IS NOT NULL
              AND TRIM(timezone_name) <> ''
            ORDER BY timezone_name
        ");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $timezoneName = trim($row['timezone_name']);

            if (!self::isValidTimezoneName($timezoneName)) {
                continue;
            }

            $timezones[] = [
                'idtimeszones' => (int) $row['idtimeszones'],
                'timezone_name' => $timezoneName,
                'utc_offset_minutos' => self::offsetMinutesForTimezone($timezoneName),
            ];
        }

        if (count($timezones) === 0) {
            foreach (self::supportedTimezones() as $timezoneName => $offsetMinutes) {
                $timezones[] = [
                    'idtimeszones' => 0,
                    'timezone_name' => $timezoneName,
                    'utc_offset_minutos' => $offsetMinutes,
                ];
            }
        }

        return $timezones;
    }

    public static function daysSinceLocalDate($date, string $timezoneName): int
    {
        if (empty($date)) {
            return 0;
        }

        $timezone = new DateTimeZone(self::normalizeTimezoneName($timezoneName));
        $start = (new DateTimeImmutable((string) $date, $timezone))->setTime(0, 0, 0);
        $today = (new DateTimeImmutable('now', $timezone))->setTime(0, 0, 0);

        return (int) $start->diff($today)->format('%r%a');
    }

    public static function cityTimezoneContext(PDO $conexion, $idciudad): array
    {
        $context = [
            'idciudad' => (int) $idciudad,
            'timezone_name' => self::DEFAULT_TIMEZONE,
            'utc_offset_minutos' => self::offsetMinutesForTimezone(self::DEFAULT_TIMEZONE),
        ];

        if (empty($idciudad)) {
            return $context;
        }

        $stmt = $conexion->prepare("
            SELECT
                idciudad,
                IFNULL(timezone_name, :default_timezone) AS timezone_name,
                IFNULL(utc_offset_minutos, :default_offset) AS utc_offset_minutos
            FROM t_ciudad
            WHERE idciudad = :idciudad
            LIMIT 1
        ");

        $stmt->execute([
            ':default_timezone' => self::DEFAULT_TIMEZONE,
            ':default_offset' => self::DEFAULT_OFFSET_MINUTES,
            ':idciudad' => (int) $idciudad,
        ]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $timezoneName = self::normalizeTimezoneName($row['timezone_name'] ?? null);

            $context = [
                'idciudad' => (int) $row['idciudad'],
                'timezone_name' => $timezoneName,
                'utc_offset_minutos' => self::offsetMinutesForTimezone($timezoneName),
            ];
        }

        return $context;
    }

    public static function warehouseTimezoneContext(PDO $conexion, $idalmacen, $idempresa = null): array
    {
        $query = "
            SELECT
                t_ciudad.idciudad,
                IFNULL(t_ciudad.timezone_name, :default_timezone) AS timezone_name,
                IFNULL(t_ciudad.utc_offset_minutos, :default_offset) AS utc_offset_minutos
            FROM t_almacen
            INNER JOIN t_ciudad ON t_almacen.idciudad = t_ciudad.idciudad
            WHERE t_almacen.idalmacen = :idalmacen
        ";

        if (!empty($idempresa)) {
            $query .= " AND t_ciudad.idempresa = :idempresa";
        }

        $query .= " LIMIT 1";

        $stmt = $conexion->prepare($query);
        $params = [
            ':default_timezone' => self::DEFAULT_TIMEZONE,
            ':default_offset' => self::DEFAULT_OFFSET_MINUTES,
            ':idalmacen' => (int) $idalmacen,
        ];

        if (!empty($idempresa)) {
            $params[':idempresa'] = (int) $idempresa;
        }

        $stmt->execute($params);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $timezoneName = self::normalizeTimezoneName($row['timezone_name'] ?? null);

            return [
                'idciudad' => (int) $row['idciudad'],
                'timezone_name' => $timezoneName,
                'utc_offset_minutos' => self::offsetMinutesForTimezone($timezoneName),
            ];
        }

        return [
            'idciudad' => 0,
            'timezone_name' => self::DEFAULT_TIMEZONE,
            'utc_offset_minutos' => self::offsetMinutesForTimezone(self::DEFAULT_TIMEZONE),
        ];
    }

    public static function ensureClaimsTimezone(PDO $conexion, array $claims): array
    {
        if (!empty($claims['timezone_name']) && isset($claims['utc_offset_minutos'])) {
            $claims['timezone_name'] = self::normalizeTimezoneName($claims['timezone_name']);
            $claims['utc_offset_minutos'] = self::offsetMinutesForTimezone($claims['timezone_name']);

            return $claims;
        }

        $context = self::cityTimezoneContext($conexion, $claims['idciudad'] ?? 0);

        $claims['idciudad'] = $context['idciudad'] ?: ($claims['idciudad'] ?? 0);
        $claims['timezone_name'] = $context['timezone_name'];
        $claims['utc_offset_minutos'] = $context['utc_offset_minutos'];

        return $claims;
    }

    public static function defaultTimezoneForCountry($country): array
    {
        $country = strtoupper(trim((string) $country));

        if (in_array($country, ['PE', 'PER', 'PERU'], true)) {
            return [
                'timezone_name' => 'America/Lima',
                'utc_offset_minutos' => self::offsetMinutesForTimezone('America/Lima'),
            ];
        }

        return [
            'timezone_name' => self::DEFAULT_TIMEZONE,
            'utc_offset_minutos' => self::offsetMinutesForTimezone(self::DEFAULT_TIMEZONE),
        ];
    }
}
