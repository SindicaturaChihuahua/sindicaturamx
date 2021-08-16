CREATE TABLE dm_s_landing (
  post_id bigint(20) UNSIGNED NOT NULL auto_increment,
  autor_id bigint(20) UNSIGNED NOT NULL,
  slug varchar(120) NOT NULL DEFAULT '',
  titulo varchar(300) NOT NULL DEFAULT '',
  tagline varchar(300) NOT NULL DEFAULT '',
  descripcion text NOT NULL,
  bloque1 longtext NOT NULL,
  bloque2 longtext NOT NULL,
  bloque3 longtext NOT NULL,
  bloque4 longtext NOT NULL,
  tipo varchar(30) NOT NULL DEFAULT 'landing',
  folder varchar(24) NOT NULL DEFAULT '',
  cover varchar(50) NOT NULL DEFAULT '',
  img1 varchar(50) NOT NULL DEFAULT '',
  img2 varchar(50) NOT NULL DEFAULT '',
  img3 varchar(50) NOT NULL DEFAULT '',
  icono1 varchar(50) NOT NULL DEFAULT '',
  icono2 varchar(50) NOT NULL DEFAULT '',
  icono3 varchar(50) NOT NULL DEFAULT '',
  archivo varchar(50) NOT NULL DEFAULT '',
  formato varchar(120) NOT NULL DEFAULT 'default',
  visitas int(11) NOT NULL DEFAULT '0',
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

CREATE TABLE dm_s_landing_archivos (
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

CREATE TABLE dm_s_landing_categorias (
  categoria_id bigint(20) UNSIGNED NOT NULL auto_increment,
  categoria_slug varchar(60) NOT NULL DEFAULT '',
  categoria_nombre varchar(50) NOT NULL DEFAULT '',
  categoria_tagline varchar(60) NOT NULL DEFAULT '',
  categoria_descripcion text NOT NULL,
  categoria_cover varchar(50) NOT NULL DEFAULT '',
  orden smallint(5) UNSIGNED NOT NULL DEFAULT '50000',
  status varchar(14) NOT NULL DEFAULT 'revision',
  PRIMARY KEY  (categoria_id)
);


CREATE TABLE dm_s_landing_relation (
  post_id bigint(20) UNSIGNED NOT NULL,
  categoria_id bigint(20) UNSIGNED NOT NULL
);

INSERT INTO dm_secciones (seccion_id, nombre, displaynombre, description, version, tab, icon, permiso, orden) VALUES (NULL, 'landing', 'Landing Pages ', 'Landing Pages', '1.0', 'editor', 'columns', 'Editor', '30000');
