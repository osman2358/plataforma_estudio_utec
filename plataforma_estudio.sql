-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS plataforma_estudio DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE plataforma_estudio;

-- =========================================================================
-- TABLAS DE CATÁLOGOS BASE
-- =========================================================================

-- 1. roles (Nueva tabla para escalabilidad futura)
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(150),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar los roles por defecto inmediatamente
INSERT INTO roles (nombre_rol, descripcion) VALUES 
('estudiante', 'Usuario regular que busca y crea grupos de estudio'),
('docente', 'Usuario con permisos de lectura para monitoreo de grupos'),
('admin', 'Administrador global del sistema');

-- 2. asignaturas 
CREATE TABLE asignaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_materia VARCHAR(20) UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =========================================================================
-- TABLAS PRINCIPALES Y RELACIONES
-- =========================================================================

-- 3. usuarios (Actualizada para apuntar a la tabla de roles)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_rol INT NOT NULL, -- Llave foránea hacia la tabla roles
    carne VARCHAR(15) UNIQUE,
    nombre_completo VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    estado ENUM('activo', 'inactivo', 'eliminado') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rol) REFERENCES roles(id) ON DELETE RESTRICT
);

-- 4. grupos_estudio 
CREATE TABLE grupos_estudio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_asignatura INT NOT NULL,
    id_creador INT, 
    nombre_grupo VARCHAR(100) NOT NULL,
    tema_especifico VARCHAR(150),
    max_integrantes INT NOT NULL,
    estado ENUM('abierto', 'lleno', 'cerrado', 'eliminado') DEFAULT 'abierto',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_creador) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- 5. miembros_grupo (Tabla Pivote)
CREATE TABLE miembros_grupo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_grupo INT NOT NULL,
    fecha_union TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE, 
    FOREIGN KEY (id_grupo) REFERENCES grupos_estudio(id) ON DELETE CASCADE,
    UNIQUE(id_usuario, id_grupo)
);

-- 6. mensajes_foro 
CREATE TABLE mensajes_foro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_grupo INT NOT NULL,
    id_usuario INT, 
    contenido TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_grupo) REFERENCES grupos_estudio(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- 7. materiales_apoyo 
CREATE TABLE materiales_apoyo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_grupo INT NOT NULL,
    id_usuario INT,
    titulo VARCHAR(100) NOT NULL,
    enlace_recurso VARCHAR(255) NOT NULL,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_grupo) REFERENCES grupos_estudio(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- =========================================================================
-- TRIGGERS DE VALIDACIÓN Y AUTOMATIZACIÓN
-- =========================================================================

DELIMITER //

CREATE TRIGGER trg_validar_cupo_grupo
BEFORE INSERT ON miembros_grupo
FOR EACH ROW
BEGIN
    DECLARE v_max_integrantes INT;
    DECLARE v_actuales INT;

    SELECT max_integrantes INTO v_max_integrantes 
    FROM grupos_estudio WHERE id = NEW.id_grupo;

    SELECT COUNT(*) INTO v_actuales 
    FROM miembros_grupo WHERE id_grupo = NEW.id_grupo;

    IF v_actuales >= v_max_integrantes THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Operación denegada: El grupo de estudio ha alcanzado su límite máximo de integrantes.';
    END IF;
END //

CREATE TRIGGER trg_actualizar_estado_grupo
AFTER INSERT ON miembros_grupo
FOR EACH ROW
BEGIN
    DECLARE v_max_integrantes INT;
    DECLARE v_actuales INT;

    SELECT max_integrantes INTO v_max_integrantes FROM grupos_estudio WHERE id = NEW.id_grupo;
    SELECT COUNT(*) INTO v_actuales FROM miembros_grupo WHERE id_grupo = NEW.id_grupo;

    IF v_actuales = v_max_integrantes THEN
        UPDATE grupos_estudio SET estado = 'lleno' WHERE id = NEW.id_grupo;
    END IF;
END //

DELIMITER ;
