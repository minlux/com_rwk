CREATE TABLE IF NOT EXISTS #__rwkteam
(
   id INT NOT NULL AUTO_INCREMENT,
   name TEXT NOT NULL,
   disciplin TEXT,
   league TEXT,
   publish BOOL DEFAULT 0,
   ordering INT DEFAULT 0,
   PRIMARY KEY (id)
);
INSERT INTO #__rwkteam (id, name, disciplin, league) VALUES (1, 'Mannschaft 1', 'Luftgewehr', 'Gauliga' );


CREATE TABLE IF NOT EXISTS #__rwkmatch
(
   id INT NOT NULL AUTO_INCREMENT,
   team INT NOT NULL,
   pass INT NOT NULL,
   uri TEXT NOT NULL,
   publish BOOL DEFAULT 0,
   updating INT DEFAULT 0,
   xml TEXT,
   PRIMARY KEY (id)
);
INSERT INTO #__rwkmatch (team, pass, uri) VALUES (1,  1, 'http://www.rwk-onlinemelder.de/online/show_sent_competitions.php?sel_discipline_id=6&sel_class_id=29&sel_date=16.11.2011&sel_combination_id=3376');
