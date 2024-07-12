-- 
-- File : teambTables.sql
-- Date : 2024/06/04
-- Author : H.Kitagawa
-- 

-- テーブル作成
DROP TABLE IF EXISTS
    TEACHER,
    SUBJECT,
    CLASS,
    LESSON,
    STUDENT,
    ATTENDANCE,
    ATTENDANCEHEADER,
    ATTENDANCEDETAIL;


-- 教員表
CREATE TABLE TEACHER (
    TEACHER_NO  INT PRIMARY KEY,        -- 教員番号
    TNAME       VARCHAR(100) NOT NULL,  -- 教員名
    PASS        VARCHAR(20) NOT NULL,   -- パスワード

    -- チェック制約
    CHECK (LENGTH(PASS) >= 8)       -- パスワードは8文字以上
);

-- 科目表
CREATE TABLE SUBJECT (
    SUBJECT_NO  INT PRIMARY KEY,        -- 科目番号
    SNAME        VARCHAR(50) NOT NULL,   -- 科目名
    LEADER      INT NOT NULL,           -- 科目主任
    SUBJECT_URL VARCHAR(1000) NOT NULL, -- 科目URL

    -- 外部キー制約
    FOREIGN KEY (LEADER) REFERENCES TEACHER(TEACHER_NO)
);

-- クラス表
CREATE TABLE CLASS (
    CLASS_NO    INT PRIMARY KEY,        -- クラス番号
    CNAME       VARCHAR(10) NOT NULL,   -- クラス名

    -- ユニーク制約
    UNIQUE(CNAME)
);

-- 授業表
CREATE TABLE LESSON (
    SUBJECT     INT,                    -- 科目
    CLASS       INT,                    -- 受講クラス
    TEACHER     INT NOT NULL,           -- 教員
    START_TIME  TIME,                   -- 開始時刻
    END_TIME    TIME,                   -- 終了時刻

    -- 複合主キー制約
    PRIMARY KEY (SUBJECT,CLASS),

    -- 外部キー制約
    FOREIGN KEY (SUBJECT) REFERENCES SUBJECT(SUBJECT_NO),
    FOREIGN KEY (CLASS) REFERENCES CLASS(CLASS_NO),
    FOREIGN KEY (TEACHER) REFERENCES TEACHER(TEACHER_NO)
);

-- 生徒表
CREATE TABLE STUDENT (
    STUDENT_NO  CHAR(7) PRIMARY KEY,        -- 学籍番号
    CLASS       INT NOT NULL,           -- クラス
    SNAME       VARCHAR(50) NOT NULL,   -- 氏名
    FACE_IMAGE     VARCHAR(255) NOT NULL,                    -- 顔id

    -- 外部キー制約
    FOREIGN KEY (CLASS) REFERENCES CLASS(CLASS_NO)
);

-- 出欠区分表
CREATE TABLE ATTENDANCE (
    ATTEND_CLASS    INT PRIMARY KEY,        -- 出欠区分
    ANAME           VARCHAR(10) NOT NULL    -- 区分名
);

-- 出欠ヘッダ表
CREATE TABLE ATTENDANCEHEADER (
    HEADER_NO   INT PRIMARY KEY,        -- 受注ヘッダ名
    SUBJECT     INT,                    -- 科目
    TEACHER     INT,                    -- 教員
    CLASS       INT,                    -- 受講クラス
    LESSON_DATE DATE,                   -- 授業日

    -- 外部キー制約
    FOREIGN KEY (SUBJECT) REFERENCES SUBJECT(SUBJECT_NO),
    FOREIGN KEY (TEACHER) REFERENCES TEACHER(TEACHER_NO),
    FOREIGN KEY (CLASS) REFERENCES CLASS(CLASS_NO)
);

-- 出欠明細表
CREATE TABLE ATTENDANCEDETAIL (
    HEADER_NO   INT,                    -- 受注ヘッダ番号
    STUDENT     CHAR(7),                    -- 学籍番号
    CAMERA      INT,                    -- カメラ確認
    CAMERA_TIME TIME,                   -- カメラ確認時刻
    QR          INT,                    -- QR確認
    QR_TIME     TIME,                   -- QR確認時刻

    -- 外部キー制約
    FOREIGN KEY (HEADER_NO) REFERENCES ATTENDANCEHEADER(HEADER_NO),
    FOREIGN KEY (STUDENT) REFERENCES STUDENT(STUDENT_NO)
);

-- 初期データ
-- 出欠区分表
INSERT INTO ATTENDANCE
VALUES
    (1, '出席'),
    (2, '欠席');

-- テストデータ追加

-- 教師表
INSERT INTO TEACHER
VALUES
    (1, '山田 太郎', 72786567),
    (2, '佐藤 花子', 97253486),
    (3, '鈴木 健一', 64897253),
    (4, '田中 美咲', 89654273),
    (5, '高橋 和也', 74265398),
    (6, '伊藤 明日香', 67289453),
    (7, '渡辺 翔太', 53864279),
    (8, '中村 優子', 28394576),
    (9, '小林 翔', 65879234),
    (10, '加藤 里奈', 62745893),
    (11, '吉田 真一', 27864395),
    (12, '山本 夏美', 49268573),
    (13, '石井 拓海', 95836427),
    (14, '斎藤 由美', 52367948),
    (15, '松本 大輔', 27346895)
;

-- 科目表
INSERT INTO SUBJECT
VALUES
    (1, 'デジタルメディアと社会変革', 11, 'https://www.ecc.jq/1'),
    (2, '量子コンピューティング入門', 3, 'https://www.ecc.jq/2'),
    (3, '環境政策と持続可能性', 2, 'https://www.ecc.jq/3'),
    (4, '古典文学の現代的解釈', 13, 'https://www.ecc.jq/4'),
    (5, '人工知能と機械学習の基礎', 11, 'https://www.ecc.jq/5'),
    (6, 'グローバル経済と国際貿易', 15, 'https://www.ecc.jq/6'),
    (7, '近代建築の歴史と理論', 1, 'https://www.ecc.jq/7'),
    (8, '神経科学と認知心理学', 1, 'https://www.ecc.jq/8'),
    (9, 'データサイエンスとビッグデータ分析', 10, 'https://www.ecc.jq/9'),
    (10, 'クリエイティブライティングとストーリーテリング', 10, 'https://www.ecc.jq/10'),
    (11, 'ソーシャルメディアマーケティング戦略', 4, 'https://www.ecc.jq/11'),
    (12, 'バイオエシックスと医療倫理', 5, 'https://www.ecc.jq/12'),
    (13, '宇宙探査と惑星科学', 14, 'https://www.ecc.jq/13'),
    (14, '異文化コミュニケーションの理論と実践', 2, 'https://www.ecc.jq/14'),
    (15, 'フィンテックとデジタル通貨の未来', 14, 'https://www.ecc.jq/15')
;

-- クラス表
INSERT INTO CLASS
VALUES
    (1, '1年A組'),
    (2, '1年B組'),
    (3, '1年C組'),
    (4, '1年D組'),
    (5, '1年E組'),
    (6, '2年A組'),
    (7, '2年B組'),
    (8, '2年C組'),
    (9, '2年D組'),
    (10, '2年E組'),
    (11, '3年A組'),
    (12, '3年B組'),
    (13, '3年C組'),
    (14, '3年D組'),
    (15, '3年E組'),
    (16, '4年A組'),
    (17, '4年B組'),
    (18, '4年C組'),
    (19, '4年D組'),
    (20, '4年E組')
;

-- 授業表
INSERT INTO LESSON
VALUES
    (1, 3, 6, '09:15', '12:30'),
    (1, 10, 1, '10:45', '12:30'),
    (2, 12, 7, '10:45', '12:30'),
    (3, 14, 3, '09:15', '11:00'),
    (5, 19, 8, '13:30', '15:00'),
    (6, 17, 11, '13:30', '15:00'),
    (7, 2, 4, '10:45', '12:30'),
    (8, 18, 6, '09:15', '12:30'),
    (9, 9, 8, '13:30', '16:45'),
    (10, 19, 12, '15:15', '16:45'),
    (11, 4, 7, '09:15', '12:30'),
    (12, 10, 15, '09:15', '11:00'),
    (14, 20, 10, '09:15', '11:00'),
    (14, 3, 1, '15:15', '16:45'),
    (15, 14, 14, '10:45', '12:30')
;

-- 生徒表
INSERT INTO STUDENT
VALUES
    ('1111111', 12, '松瀬 美咲', 35778),
    ('2222222', 9, '二村 千沙', 54544),
    ('3333333', 16, '平沢 真由美', 35188),
    ('4444444', 5, '福重 良太', 57005),
    ('5555555', 17, '天満 かおり', 44507),
    ('6666666', 11, '畑田 健', 34512),
    ('7777777', 19, '河上 義弘', 19368),
    ('8888888', 4, 'Khin Thein Zaw', 10261),
    ('9999999', 18, '中条 るみ', 51450),
    ('0000001', 6, '増渕 孝弘', 61831),
    ('0000002', 20, '福本 真理', 79815),
    ('0000003', 13, '矢田 澄香', 99715),
    ('0000004', 7, 'Tin Maung Lwin', 38376),
    ('0000005', 2, '近松 志保', 14681),
    ('0000006', 15, '大草 琢磨', 72190),
    ('0000007', 14, '増山 俊', 80796),
    ('0000008', 1, '新倉 圭吾', 66550),
    ('0000009', 10, '里見 鈴奈', 76865),
    ('0000010', 3, '池本 詩織', 11051),
    ('0000011', 8, '神崎 英理', 87364),
    ('0000012', 17, '赤石 紘一', 78485),
    ('0000013', 5, '伊達 あゆみ', 60660),
    ('0000014', 20, '川名 雄一', 43546),
    ('0000015', 19, '石橋 明人', 29032),
    ('0000016', 13, 'Kim Min-joon', 30187),
    ('0000017', 9, '川田 唯', 19132),
    ('0000018', 2, '小寺 和人', 85432),
    ('0000019', 12, '武石 惟子', 75745),
    ('0000020', 8, 'Lee Seo-yeon', 61342),
    ('0000021', 15, '羽石 菜月', 15333)
;

-- 出欠ヘッダ表
INSERT INTO ATTENDANCEHEADER
VALUES
    (1, 5, 15, 19, '2023-09-17'),
    (2, 1, 14, 10, '2024-02-19'),
    (3, 7, 9, 2, '2023-10-12'),
    (4, 11, 10, 4, '2023-09-09'),
    (5, 8, 15, 18, '2024-01-06'),
    (6, 8, 14, 18, '2023-07-20'),
    (7, 1, 7, 3, '2023-07-06'),
    (8, 9, 9, 9, '2024-01-01'),
    (9, 2, 10, 12, '2023-09-06'),
    (10, 3, 9, 14, '2023-10-10'),
    (11, 9, 12, 9, '2023-06-27'),
    (12, 6, 6, 17, '2024-01-15'),
    (13, 2, 4, 12, '2024-04-28'),
    (14, 15, 8, 14, '2023-11-29'),
    (15, 7, 6, 2, '2024-06-02'),
    (16, 5, 14, 19, '2024-02-06'),
    (17, 8, 1, 18, '2023-09-09'),
    (18, 6, 14, 17, '2024-03-16'),
    (19, 14, 1, 20, '2023-07-17'),
    (20, 15, 13, 14, '2024-03-30')
;

-- 出欠明細表
INSERT INTO ATTENDANCEDETAIL
VALUES
    (3, '2222222', 1, '10:50:00', 2, NULL),
    (3, '0000001', 1, '10:46:00', 2, NULL),
    (4, '3333333', 1, '09:18:00', 1, '09:20:00'),
    (4, '0000011', 1, '09:19:00', 1, '09:17:00'),
    (6, '1111111', 2, NULL, 2, NULL),
    (6, '0000002', 1, '09:16:00', 1, '09:17:00'),
    (6, '0000003', 1, '09:19:00', 1, '09:18:00'),
    (7, '4444444', 1, '09:21:00', 1, '09:18:00'),
    (7, '5555555', 1, '09:17:00', 2, NULL),
    (17, '6666666', 1, '09:15:00', 1, '09:22:00'),
    (18, '8888888', 2, NULL, 1, '13:35:00'),
    (18, '2222222', 1, '13:31:00', 1, '13:34:00'),
    (19, '9999999', 1, '09:21:00', 1, '09:17:00')
;

