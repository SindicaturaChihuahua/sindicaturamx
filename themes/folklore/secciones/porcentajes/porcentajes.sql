CREATE TABLE dm_s_porcentajes (
  post_id bigint(20) UNSIGNED NOT NULL auto_increment,
  autor_id bigint(20) UNSIGNED NOT NULL,
  categoria_id bigint(20) UNSIGNED NOT NULL,
  slug varchar(120) NOT NULL DEFAULT '',
  titulo varchar(240) NOT NULL DEFAULT '',
  tagline varchar(110) NOT NULL DEFAULT '',
  porcentaje varchar(110) NOT NULL DEFAULT '',
  descripcion text NOT NULL,
  video varchar(240) NOT NULL DEFAULT '',
  tipo varchar(30) NOT NULL DEFAULT 'rubro',
  folder varchar(24) NOT NULL DEFAULT '',
  cover varchar(50) NOT NULL DEFAULT '',
  img varchar(50) NOT NULL DEFAULT '',
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

CREATE TABLE dm_s_porcentajes_archivos (
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

CREATE TABLE dm_s_porcentajes_categorias (
  categoria_id bigint(20) UNSIGNED NOT NULL auto_increment,
  categoria_slug varchar(60) NOT NULL DEFAULT '',
  categoria_nombre varchar(100) NOT NULL DEFAULT '',
  categoria_tagline varchar(100) NOT NULL DEFAULT '',
  categoria_descripcion text NOT NULL,
  categoria_cover varchar(50) NOT NULL DEFAULT '',
  categoria_formato varchar(50) NOT NULL DEFAULT 'default',
  orden smallint(5) UNSIGNED NOT NULL DEFAULT '50000',
  status varchar(14) NOT NULL DEFAULT 'revision',
  PRIMARY KEY  (categoria_id)
);


CREATE TABLE dm_s_porcentajes_relation (
  post_id bigint(20) UNSIGNED NOT NULL,
  categoria_id bigint(20) UNSIGNED NOT NULL
);

INSERT INTO dm_secciones (seccion_id, nombre, displaynombre, description, version, tab, icon, permiso, orden) VALUES (NULL, 'porcentajes', 'Porcentajes de Cumplimiento', 'Porcentajes de Cumplimiento', '1.0', 'contraloria', 'percent', 'Editor', '30000');
