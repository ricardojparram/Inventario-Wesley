-- SCRIPT PRIVILEGIOS DE USUARIOS Y GRUPOS DE USUARIOS INVENTARIO WESLEY

-- USUARIOS:

-- Usuario administrador:

CREATE USER 'administrador' @'%' IDENTIFIED BY 'clave_administrador';
GRANT ALL PRIVILEGES ON wesley.* TO 'administrador' @'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;

-- Usuario operador:

CREATE USER 'operador' @'%' IDENTIFIED BY 'clave_operador';

GRANT 
    CREATE,
    DROP,
    EVENT,
    TRIGGER,
    CREATE ROUTINE,
    ALTER ROUTINE,
    CREATE VIEW,
    SHOW VIEW,
    CREATE USER,
    RELOAD,
    FILE 
    ON wesley.* TO 'operador' @'%';

GRANT 
    CREATE,
    DROP,
    INDEX,
    ALTER 
    ON wesley.* TO 'operador' @'%';

GRANT
    LOCK TABLES,
    REPLICATION CLIENT,
    REPLICATION SLAVE 
    ON wesley.* TO 'operador' @'%';

GRANT ALL PRIVILEGES ON wesley.* TO 'operador' @'%';
FLUSH PRIVILEGES;

-- Usuario analista:

CREATE USER 'analista' @'%' IDENTIFIED BY 'clave_analista';

GRANT 
    CREATE,
    DROP,
    EVENT,
    TRIGGER,
    CREATE ROUTINE,
    ALTER ROUTINE,
    CREATE VIEW,
    SHOW VIEW 
    ON wesley.* TO 'analista' @'%';

GRANT 
    CREATE,
    DROP,
    INDEX,
    ALTER 
    ON wesley.* TO 'analista' @'%';

GRANT ALL PRIVILEGES ON *.* TO 'analista' @'%';
FLUSH PRIVILEGES;

-- Usuario auditor:
CREATE USER 'auditor' @'%' IDENTIFIED BY 'clave_auditor';
GRANT SELECT ON wesley.* TO 'auditor' @'%';
FLUSH PRIVILEGES;

-- Usuario de la web:
CREATE USER 'usuario_web'@'%' IDENTIFIED BY '1234';
GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'usuario_web'@'%' 
FLUSH PRIVILEGES;

-- GRUPOS (ROLES)

-- Usuarios normales:
CREATE ROLE 'usuario_normal';
GRANT 
    SELECT,
    INSERT,
    UPDATE,
    DELETE 
    ON wesley.* TO 'usuario_normal';
FLUSH PRIVILEGES;

-- Usuarios básicos:
CREATE ROLE 'usuario_basico';
GRANT 
    SELECT,
    INSERT 
    ON wesley.* TO 'usuario_basico';
    
-- Usuarios avanzados:
CREATE ROLE 'usuario_avanzado';
GRANT 
    SELECT,
    INSERT,
    UPDATE,
    DELETE 
    ON wesley.* TO 'usuario_avanzado';

-- Usuarios desarrolladores:
CREATE ROLE 'usuario_desarrollador';

GRANT 
    CREATE,
    DROP,
    ALTER,
    CREATE ROUTINE,
    ALTER ROUTINE,
    CREATE VIEW,
    SHOW VIEW,
    CREATE TRIGGER,
    EVENT,
    INDEX 
    ON *.* TO 'usuario_desarrollador';

GRANT ALL PRIVILEGES ON wesley.* TO 'usuario_desarrollador';

-- Crear y asignar usuarios a los roles correspondientes:

-- Usuarios normales:
CREATE USER 'usuario_normal_1'@'%' IDENTIFIED BY 'clave_normal_1';
CREATE USER 'usuario_normal_2'@'%' IDENTIFIED BY 'clave_normal_2';
GRANT 'usuario_normal' TO 'usuario_normal_1'@'%';
GRANT 'usuario_normal' TO 'usuario_normal_2'@'%';
SET DEFAULT ROLE 'usuario_normal' FOR 'usuario_normal_1'@'%'; 
SET DEFAULT ROLE 'usuario_normal' FOR 'usuario_normal_2'@'%'; 
FLUSH PRIVILEGES;

-- Usuarios básicos:
CREATE USER 'usuario_basico_1'@'%' IDENTIFIED BY 'clave_basico_1';
CREATE USER 'usuario_basico_2'@'%' IDENTIFIED BY 'clave_basico_2';
GRANT 'usuario_basico' TO 'usuario_basico_1'@'%';
GRANT 'usuario_basico' TO 'usuario_basico_2'@'%';
SET DEFAULT ROLE 'usuario_basico' FOR 'usuario_basico_1'@'%'; 
SET DEFAULT ROLE 'usuario_basico' FOR 'usuario_basico_2'@'%'; 
FLUSH PRIVILEGES;

-- Usuarios avanzados:
CREATE USER 'usuario_avanzado_1'@'%' IDENTIFIED BY 'clave_avanzado_1';
CREATE USER 'usuario_avanzado_2'@'%' IDENTIFIED BY 'clave_avanzado_2';
GRANT 'usuario_avanzado' TO 'usuario_avanzado_1'@'%';
GRANT 'usuario_avanzado' TO 'usuario_avanzado_2'@'%';
SET DEFAULT ROLE 'usuario_avanzado' FOR 'usuario_avanzado_1'@'%'; 
SET DEFAULT ROLE 'usuario_avanzado' FOR 'usuario_avanzado_2'@'%'; 
FLUSH PRIVILEGES;

-- Usuarios desarrolladores:
CREATE USER 'usuario_desarrollador_1'@'%' IDENTIFIED BY 'clave_desarrollador_1';
CREATE USER 'usuario_desarrollador_2'@'%' IDENTIFIED BY 'clave_desarrollador_2';
GRANT 'usuario_desarrollador' TO 'usuario_desarrollador_1'@'%';
GRANT 'usuario_desarrollador' TO 'usuario_desarrollador_2'@'%';
SET DEFAULT ROLE 'usuario_desarrollador' FOR 'usuario_desarrollador_1'@'%'; 
SET DEFAULT ROLE 'usuario_desarrollador' FOR 'usuario_desarrollador_2'@'%'; 

FLUSH PRIVILEGES;