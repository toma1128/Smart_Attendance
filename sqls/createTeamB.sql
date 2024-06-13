--
-- File : createTeamB.sql
-- Date : 2024/06/04
-- Author : H.Kitagawa

-- DB作成 (DB名 : teamb)
DROP DATABASE IF EXISTS teamb;
CREATE DATABASE teamb;

-- ユーザ作成 (user名 : test)
DROP USER IF EXISTS test;
CREATE USER 'test'@'%' IDENTIFIED BY 'test';

-- ユーザへの権限付与
GRANT ALL ON teamb.* TO test;

-- 変更適用
FLUSH PRIVILEGES;
-- ユーザ確認
SHOW GRANTS FOR 'test'@'%';

-- データベース移動
USE teamb;

-- テーブル作成SQL実行
SOURCE teambTables.sql;

-- 作成テーブル確認
SHOW TABLES;

SHOW COLUMNS FROM TEACHER;
SHOW COLUMNS FROM SUBJECT;
SHOW COLUMNS FROM CLASS;
SHOW COLUMNS FROM LESSON;
SHOW COLUMNS FROM STUDENT;
SHOW COLUMNS FROM ATTENDANCE;
SHOW COLUMNS FROM ATTENDANCEHEADER;
SHOW COLUMNS FROM ATTENDANCEDETAIL;

SELECT * FROM TEACHER;
SELECT * FROM SUBJECT;
SELECT * FROM CLASS;
SELECT * FROM LESSON;
SELECT * FROM STUDENT;
SELECT * FROM ATTENDANCE;
SELECT * FROM ATTENDANCEHEADER;
SELECT * FROM ATTENDANCEDETAIL;