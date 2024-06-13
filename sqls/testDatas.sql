--
-- File : testDatas.sql
-- Date : 2024/06/06
-- Author : H.Kitagawa
--

-- テストデータ表示
-- 教師表
select
    teacher_no          as '教員管理番号',
    tname               as '氏名',
    pass                as 'パスワード'
from teacher;

-- 科目表
select
    sub.subject_no      as '科目番号',
    sub.sname           as '科目名',
    tea.tname           as '科目主任',
    sub.SUBJECT_URL     as '授業URL'
from subject            as sub
    left join teacher   as tea on (sub.leader = tea.teacher_no);

-- クラス表
select
    class_no            as 'クラス番号',
    cname               as 'クラス名'
from class;

-- 授業表
select
    sub.sname           as '科目',
    cla.cname           as '受講クラス',
    tea.tname           as '教員',
    les.start_time      as '開始時刻',
    les.end_time        as '終了時刻'
from lesson             as les
    left join subject   as sub on (les.subject = sub.subject_no)
    left join class     as cla on (les.class = cla.class_no)
    left join teacher   as tea on (les.teacher = tea.teacher_no);

-- 生徒表
select
    stu.student_no      as '学籍番号',
    cla.cname           as 'クラス名',
    stu.sname           as '氏名',
    stu.face_image      as '顔写真パス'
from student            as stu
    left join class     as cla on (cla.class_no = stu.class);

-- 出欠区分
select
    attend_class        as '出欠区分',
    aname               as '区分名'
from attendance;

-- 出欠ヘッダ表
select
    hea.header_no       as 'ヘッダ番号',
    sub.sname           as '科目',
    tea.tname           as '担当教員',
    cla.cname           as 'クラス',
    hea.lesson_date     as '授業日'
from attendanceheader   as hea
    left join subject   as sub on (sub.subject_no = hea.subject)
    left join teacher   as tea on (tea.teacher_no = hea.teacher)
    left join class     as cla on (cla.class_no = hea.class);


-- 出欠明細表
select
	hea.header_no               as 'ヘッダ番号',
	stu.sname                   as '生徒名',
	(
        SELECT 
            att.aname 
	    FROM attendance         as att 
	    WHERE
            att.attend_class = det.camera
    )                           as 'カメラ確認',

	det.camera_time             as 'カメラ確認時刻',
	(
        SELECT
            att.aname 
        FROM attendance as att 
	    WHERE 
            att.attend_class = det.qr
    )                           as 'QR確認',

	det.qr_time                 as 'QR確認時刻',

    -- 両方出席なら○、片方なら△、どちらも未確認なら✖を表示
	case
		when det.camera = 1 and det.qr = 1 then '○'
		when det.camera = 1 or det.qr = 1 then '△'
		else '×'
	end                         as '出欠'

from attendancedetail           as det
    left join attendanceheader  as hea on (hea.header_no = det.header_no)
    left join student           as stu on (stu.student_no = det.student)
order by det.camera, det.qr;    -- ○、△、✖の順にソート