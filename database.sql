DROP DATABASE IF EXISTS webt_doctrine;
CREATE DATABASE IF NOT EXISTS webt_doctrine;
use webt_doctrine;

CREATE TABLE IF NOT EXISTS Player
(
    pk_player_ID int auto_increment,
    Vorname      varchar(255),
    Nachname     varchar(255),

    PRIMARY KEY (pk_player_ID)
);

CREATE TABLE IF NOT EXISTS Symbol
(
    pk_symbol_ID int auto_increment,
    Bezeichnung  varchar(6),
    PRIMARY KEY (pk_symbol_ID)
);

CREATE TABLE IF NOT EXISTS Round
(
    pk_round_ID    int auto_increment,
    fk_pk_Player1  int,
    fk_pk_Player2  int,
    fk_pk_SymbolP1 int,
    fk_pk_SymbolP2 int,
    PRIMARY KEY (pk_round_ID)
);

ALTER TABLE Round
    ADD CONSTRAINT fkc_Player1_Player FOREIGN KEY (fk_pk_Player1) REFERENCES Player (pk_player_ID);
ALTER TABLE Round
    ADD CONSTRAINT fkc_Player2_Player FOREIGN KEY (fk_pk_Player2) REFERENCES Player (pk_player_ID);
ALTER TABLE Round
    ADD CONSTRAINT fkc_SymbolP1_Symbol FOREIGN KEY (fk_pk_SymbolP1) REFERENCES Symbol (pk_symbol_id);
ALTER TABLE Round
    ADD CONSTRAINT fkc_SymbolP2_Symbol FOREIGN KEY (fk_pk_SymbolP2) REFERENCES Symbol (pk_symbol_id);


INSERT INTO Player (Vorname, Nachname)
VALUES ('Nico', 'Zach'),
       ('Eyüp', 'Özbege'),
       ('Antonio', 'Vassilev'),
       ('Christian', 'Widauer');

INSERT INTO Symbol (Bezeichnung)
    VALUE ('Schere'),
    ('Stein'),
    ('Papier');

INSERT INTO Round (fk_pk_Player1, fk_pk_Player2, fk_pk_SymbolP1, fk_pk_SymbolP2)
VALUES (1, 2, 1, 1),
       (2, 1, 3, 2),
       (3, 4, 1, 3),
       (2, 4, 1, 2),
       (1, 4, 2, 1);

SELECT * FROM Player;

SELECT * FROM Symbol;

SELECT pk_round_ID, p1.vorname, p1.Nachname, sp1.Bezeichnung, p2.Vorname, p2.Nachname, sp2.Bezeichnung
FROM Round
         JOIN Player p1 on fk_pk_Player1 = p1.pk_player_ID
         JOIN Player p2 on fk_pk_Player2 = p2.pk_player_ID
         JOIN Symbol sp1 on fk_pk_SymbolP1 = sp1.pk_symbol_ID
         JOIN Symbol sp2 on fk_pk_SymbolP2 = sp2.pk_symbol_ID
ORDER BY pk_round_ID;