CREATE TABLE dm_navegacion (
  nav_id bigint(20) unsigned NOT NULL auto_increment,
  autor_id bigint(20) unsigned NOT NULL,
  tipo varchar(40) NOT NULL default 'web-address',
  titulo varchar(100) NOT NULL default '',
  linka varchar(350) NOT NULL default '',
  target varchar(20) NOT NULL default '_self',
  status varchar(14) NOT NULL default 'revision',
  PRIMARY KEY  (nav_id)
)