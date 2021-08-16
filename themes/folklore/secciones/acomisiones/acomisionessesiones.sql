CREATE TABLE dm_s_acomisiones_sesiones (
  sesion_id bigint(20) UNSIGNED NOT NULL auto_increment,
  comision_id bigint(20) UNSIGNED NOT NULL,
  sesion_slug varchar(120) NOT NULL DEFAULT '',
  sesion_nombre varchar(120) NOT NULL DEFAULT '',
  sesion_tagline varchar(200) NOT NULL DEFAULT '',
  sesion_video varchar(300) NOT NULL DEFAULT '',
  sesion_descripcion text NOT NULL,
  sesion_acta varchar(50) NOT NULL DEFAULT '',
  sesion_cover varchar(50) NOT NULL DEFAULT '',
  sesion_icono varchar(50) NOT NULL DEFAULT '',
  sesion_archivo varchar(50) NOT NULL DEFAULT '',
  visibilidad varchar(14) NOT NULL DEFAULT 'publico',
  creado datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modificado datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  orden smallint(5) UNSIGNED NOT NULL DEFAULT '50000',
  status varchar(14) NOT NULL DEFAULT 'revision',
  PRIMARY KEY  (sesion_id)
);

CREATE TABLE dm_s_acomisiones_sesiones_archivos (
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
