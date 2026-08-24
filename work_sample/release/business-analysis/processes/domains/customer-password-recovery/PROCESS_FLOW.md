# Customer Password Recovery - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

1. El usuario abre recuperacion desde la pantalla de login.
2. El frontend envia nombre, apellido y usuario/correo a `generateToken.php`.
3. ASGARD busca un usuario cliente activo por `username` o `correo`.
4. Si existe, genera un codigo alfanumerico de 6 caracteres.
5. ASGARD registra correo, token, datos del solicitante y usuario cliente en `dav_reseteos_passswords_clientes`.
6. ASGARD envia el correo de verificacion mediante SendGrid.
7. El usuario introduce usuario/correo y token en el segundo paso.
8. ASGARD valida que exista una solicitud no eliminada con ese token.
9. ASGARD compara la fecha de creacion del token contra la fecha actual.
10. Si la validacion es correcta, ASGARD marca `deleted_at` en la solicitud y devuelve el identificador de recuperacion.
11. El usuario define una nueva contrasena.
12. ASGARD consulta correo y usuario cliente desde la solicitud validada.
13. ASGARD calcula hash bcrypt y actualiza la contrasena del usuario cliente.
14. ASGARD limpia `fechabloqueo`, reinicia `intentos`, actualiza `fechacontrasena` y `updated_at`.

## Excepciones observadas

- Si el usuario no se encuentra activo, se devuelve error de comprobacion.
- Si SendGrid no responde con estado `202`, se devuelve error de envio.
- Si el token corresponde a un dia anterior, se rechaza como expirado.
- Si el token se valida, se invalida inmediatamente con `deleted_at`.
