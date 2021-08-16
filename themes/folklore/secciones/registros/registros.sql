CREATE TABLE dm_s_registros (
  post_id bigint(20) unsigned NOT NULL auto_increment,
  autor_id bigint(20) unsigned NOT NULL,
  comision_id bigint(20) unsigned NOT NULL,
  slug varchar(120) NOT NULL default '',
  nombre varchar(300) NOT NULL default '',
  correo varchar(400) NOT NULL default '',
  informacion TEXT NOT NULL default '',
  txt TEXT NOT NULL default '',
  orden int NOT NULL default 1000,
  visibilidad varchar(14) NOT NULL default 'publico',
  status varchar(14) NOT NULL default 'publicado',
  creado datetime NOT NULL default '0000-00-00 00:00:00',
  modificado datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (post_id)
);
INSERT INTO dm_secciones (seccion_id, nombre, displaynombre, description, version, tab, icon, permiso, orden) VALUES (NULL, 'registros', 'Registros', 'Registros', '1.0', 'comunicacion', 'check', 'Editor', '30000');
