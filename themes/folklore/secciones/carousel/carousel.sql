CREATE TABLE dm_s_carousel (
  post_id bigint(20) unsigned NOT NULL auto_increment,
  autor_id bigint(20) unsigned NOT NULL,
  slug varchar(120) NOT NULL default '',
  titulo varchar(300) NOT NULL default '',
  tagline varchar(400) NOT NULL default '',
  cover varchar(50) NOT NULL default '',
  img varchar(50) NOT NULL default '',
  descripcion TEXT NOT NULL default '',
  btn_texto varchar(300) NOT NULL default 'Más información',
  btn_link varchar(300) NOT NULL default '',
  formato varchar(30) NOT NULL default 'default',
  orden int NOT NULL default 1000,
  visibilidad varchar(14) NOT NULL default 'publico',
  status varchar(14) NOT NULL default 'revision',
  creado datetime NOT NULL default '0000-00-00 00:00:00',
  modificado datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (post_id)
);
INSERT INTO dm_secciones (seccion_id, nombre, displaynombre, description, version, tab, icon, permiso, orden) VALUES (NULL, 'carousel', 'Carrusel', 'Carrusel', '1.0', 'editor', 'certificate', 'Editor', '30000');
