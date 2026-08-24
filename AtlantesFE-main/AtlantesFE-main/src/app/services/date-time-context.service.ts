import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import jwt_decode from 'jwt-decode';

export interface UserTimeContext {
    idciudad: number;
    timezone_name: string;
    utc_offset_minutos: number;
}

@Injectable({
    providedIn: 'root'
})
export class DateTimeContextService {
    private readonly defaultTimezoneName = 'America/La_Paz';
    private readonly defaultUtcOffsetMinutes = -240;

    constructor(private cookies: CookieService) {}

    getTokenDetalle(): any {
        const token = this.cookies.get('token');

        if (!token) {
            return null;
        }

        return jwt_decode(token);
    }

    context(): UserTimeContext {
        const tokenDetalle = this.getTokenDetalle();

        return {
            idciudad: Number(tokenDetalle?.idciudad ?? 0),
            timezone_name: tokenDetalle?.timezone_name ?? this.defaultTimezoneName,
            utc_offset_minutos: Number(tokenDetalle?.utc_offset_minutos ?? this.defaultUtcOffsetMinutes)
        };
    }

    timezoneName(): string {
        return this.context().timezone_name;
    }

    utcOffsetMinutes(): number {
        return this.context().utc_offset_minutos;
    }

    formatDateOnly(value: string | Date | null | undefined): string {
        if (!value) {
            return '';
        }

        if (typeof value === 'string') {
            return value.substring(0, 10);
        }

        return this.toDateFilterValue(value);
    }

    toDateFilterValue(value: string | Date): string {
        if (typeof value === 'string') {
            return value.substring(0, 10);
        }

        const year = value.getFullYear();
        const month = String(value.getMonth() + 1).padStart(2, '0');
        const day = String(value.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    currentDateFilterValue(): string {
        const parts = this.currentDateTimeParts();

        return `${parts.year}-${parts.month}-${parts.day}`;
    }

    private currentDateTimeParts(): any {
        try {
            const formatter = new Intl.DateTimeFormat('en-US', {
                timeZone: this.timezoneName(),
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const formattedParts: any = {};

            formatter.formatToParts(new Date()).forEach(part => {
                if (part.type !== 'literal') {
                    formattedParts[part.type] = part.value;
                }
            });

            return {
                year: formattedParts.year,
                month: formattedParts.month,
                day: formattedParts.day,
                hour: formattedParts.hour === '24' ? '00' : formattedParts.hour,
                minute: formattedParts.minute,
                second: formattedParts.second
            };
        } catch (e) {
            const userLocalDate = new Date(Date.now() + this.utcOffsetMinutes() * 60000);

            return {
                year: String(userLocalDate.getUTCFullYear()),
                month: String(userLocalDate.getUTCMonth() + 1).padStart(2, '0'),
                day: String(userLocalDate.getUTCDate()).padStart(2, '0'),
                hour: String(userLocalDate.getUTCHours()).padStart(2, '0'),
                minute: String(userLocalDate.getUTCMinutes()).padStart(2, '0'),
                second: String(userLocalDate.getUTCSeconds()).padStart(2, '0')
            };
        }
    }
}
