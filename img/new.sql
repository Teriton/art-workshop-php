-- Добавить технику
INSERT INTO `technique` (`name`, `description`) 
VALUES ('База', 'Для начинающих художников');

-- Добавить уровень сложности
INSERT INTO `level` (`name`) 
VALUES ('Новичок');

-- Добавить мастера
INSERT INTO `master` (`first_name`, `last_name`, `specialization`, `experience_years`, `bio`) 
VALUES ('Виталя', 'Шпак', 'База', 8, 'Специалист в кибербезе однако любит порисовать.');

INSERT INTO `workshop` (
    `technique_id`,
    `level_id`,
    `master_id`,
    `name`,
    `difficulty_level`,
    `duration_minutes`,
    `cost`,
    `status`
) VALUES (
    1,                    -- technique_id (Bharatanatyam)
    2,                    -- level_id (Intermediate)
    1,                    -- master_id (Anusha Reddy)
    'Привет новичкам',
    'Новичок',
    120,                  -- 2 hours
    2.00,               -- cost in currency units
    'active'
);