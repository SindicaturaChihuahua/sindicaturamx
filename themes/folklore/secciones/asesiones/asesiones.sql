CREATE TABLE dm_s_asesiones (
  post_id bigint(20) UNSIGNED NOT NULL auto_increment,
  autor_id bigint(20) UNSIGNED NOT NULL,
  categoria_id bigint(20) UNSIGNED NOT NULL,
  slug varchar(120) NOT NULL DEFAULT '',
  titulo varchar(240) NOT NULL DEFAULT '',
  tagline varchar(110) NOT NULL DEFAULT '',
  descripcion text NOT NULL,
  video varchar(240) NOT NULL DEFAULT '',
  tipo varchar(30) NOT NULL DEFAULT 'sesion',
  folder varchar(24) NOT NULL DEFAULT '',
  cover varchar(50) NOT NULL DEFAULT '',
  acta varchar(50) NOT NULL DEFAULT '',
  icono varchar(50) NOT NULL DEFAULT '',
  archivo varchar(50) NOT NULL DEFAULT '',
  visitas int(11) NOT NULL DEFAULT '0',
  destacado int(1) NOT NULL DEFAULT '0',
  orden int(11) NOT NULL DEFAULT '3000',
  visibilidad varchar(14) NOT NULL DEFAULT 'publico',
  status varchar(14) NOT NULL DEFAULT 'revision',
  creado datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modificado datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  seo_titulo varchar(300) NOT NULL DEFAULT '',
  seo_descripcion varchar(300) NOT NULL DEFAULT '',
  extra longtext NOT NULL DEFAULT '',
  PRIMARY KEY  (post_id)
);

CREATE TABLE dm_s_asesiones_archivos (
  aid bigint(20) UNSIGNED NOT NULL auto_increment,
  objeto_id bigint(20) UNSIGNED NOT NULL,
  nombre varchar(255) NOT NULL DEFAULT '',
  descripcion varchar(350) NOT NULL DEFAULT '',
  tipo varchar(50) NOT NULL DEFAULT 'general',
  folder varchar(80) NOT NULL DEFAULT '',
  filetype varchar(50) NOT NULL DEFAULT '',
  status varchar(20) NOT NULL DEFAULT 'publish',
  orden int(11) NOT NULL DEFAULT '400',
  PRIMARY KEY  (aid)
);

INSERT INTO dm_secciones (seccion_id, nombre, displaynombre, description, version, tab, icon, permiso, orden) VALUES (NULL, 'asesiones', 'Sesiones de Cabildo', 'Sesiones de Cabildo', '1.0', 'ayuntamiento', 'university', 'Editor', '30000');
