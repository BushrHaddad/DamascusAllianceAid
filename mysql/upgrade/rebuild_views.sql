DROP VIEW IF EXISTS email_list;
CREATE VIEW email_list AS
    SELECT fam_Email AS email, 'family' AS type, fam_id AS id FROM family_fam WHERE fam_email IS NOT NULL AND fam_email != '' 
    UNION 
    SELECT per_email AS email, 'person_home' AS type, per_id AS id FROM person_per WHERE per_email IS NOT NULL AND per_email != '' 
    UNION 
    SELECT per_WorkEmail AS email, 'person_work' AS type, per_id AS id FROM person_per WHERE per_WorkEmail IS NOT NULL AND per_WorkEmail != '';
    
    
DROP VIEW IF EXISTS email_count; 
CREATE VIEW email_count AS    
    SELECT email, COUNT(*) AS total FROM email_list group by email;

DROP VIEW IF EXISTS families_view;
CREATE VIEW families_view AS 
    select `f`.`fam_ID` AS `id`,`f`.`old_id` AS `old_id`,`f`.`imported_p` AS `p`,
        `f`.`fam_Address1` AS `address1`,`f`.`fam_Address2` AS `address2`,`f`.`fam_City` AS `city`,
        `f`.`fam_State` AS `state`,`f`.`fam_HomePhone` AS `home_phone`,
        `f`.`fam_WorkPhone` AS `aid_phone`,`f`.`fam_CellPhone` AS `mobile_phone`,
        case when `f`.`fam_DateDeactivated` is null then 'active' when `f`.`fam_DateDeactivated` is not null then 'cancelled' end AS `status`,
        `c`.`c1` AS `aid_note`,`c`.`c2` AS `general_note`,`c`.`c3` AS `team_note`,
        case when `c`.`c4` = 1 then 'مهجر' when `c`.`c4` = 2 then 'مقيم' end AS `ref`,
        case when `c`.`c5` = 1 then 'عضو' when `c`.`c5` = 2 then 'مؤمن' when `c`.`c5` = 3 then 'زائر' end AS `membership_status`,
        `c`.`c6` AS `members_num`,`c`.`c7` AS `children`,`c`.`c8` AS `poverty_rate`,
        `c`.`c9` AS `main_name`,`c`.`c10` AS `partner_name`,`c`.`c11` AS `main_id`,
        `c`.`c12` AS `partner_id`,`c`.`c14` AS `no_money`,
        `c`.`c15` AS `other_notes`, `c`.`c16` AS `verifying_question` from (`family_fam` `f` join `family_custom` `c` on(`f`.`fam_ID` = `c`.`fam_ID`));

DROP VIEW IF EXISTS master_general_view;
CREATE VIEW `master_general_view` AS 
    select `master_family_master`.`id` AS `master_id`,
        `master_family_master`.`family_id` AS `family_id`,
        `master_bags`.`name` AS `bag_name`,
        `master_cash`.`name` AS `cash_name`,
        `master_dates_months`.`id` AS `month_id`,
        `master_dates_months`.`name` AS `en_month_name`,
        `master_dates_months`.`note1` AS `month_name`,
        `master_dates_year`.`id` AS `year_id`,
        `master_dates_year`.`name` AS `year_name`,
        `master_suppliments`.`name` AS `sup_name`,
        `master_teams`.`name` AS `team_name`,
        `master_visiting`.`name` AS `visiting_name`
        from (((((
            ((`master_family_master` left join `master_bags` on(`master_bags`.`id` = `master_family_master`.`bag_id`))
            left join `master_cash` on(`master_cash`.`id` = `master_family_master`.`cash_id`)) 
            left join `master_dates_months` on(`master_dates_months`.`id` = `master_family_master`.`month_id`))
            left join `master_dates_year` on(`master_dates_year`.`id` = `master_family_master`.`year_id`))
            left join `master_suppliments` on(`master_suppliments`.`id` = `master_family_master`.`sup_id`)) 
            left join `master_teams` on(`master_teams`.`id` = `master_family_master`.`team_id`))
            left join `master_visiting` on(`master_visiting`.`id` = `master_family_master`.`visited_id`)) order by `master_family_master`.`id`;



