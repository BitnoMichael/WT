-- Заполнение таблицы users
INSERT INTO users (username, password_hash, salt) VALUES
('user1', 'hashed_password_1', 'salt1'),
('user2', 'hashed_password_2', 'salt2'),
('user3', 'hashed_password_3', 'salt3');

-- Заполнение таблицы attractions
INSERT INTO attractions (name, description, location) VALUES
('Eiffel Tower', 'Iconic Parisian landmark', 'Paris, France'),
('Great Wall of China', 'Ancient Chinese fortification', 'China'),
('Machu Picchu', 'Incan citadel in the Andes', 'Peru'),
('Colosseum', 'Ancient Roman amphitheater', 'Rome, Italy'),
('Taj Mahal', 'Ivory-white marble mausoleum', 'Agra, India'),
('Grand Canyon', 'Steep-sided canyon carved by the Colorado River', 'Arizona, USA'),
('Sydney Opera House', 'Multi-venue performing arts centre', 'Sydney, Australia'),
('Christ the Redeemer', 'Art Deco statue of Jesus Christ', 'Rio de Janeiro, Brazil'),
('Louvre Museum', 'World''s largest art museum', 'Paris, France'),
('Golden Gate Bridge', 'Suspension bridge spanning the Golden Gate strait', 'San Francisco, USA');

-- Заполнение таблицы reviews (примеры отзывов)
INSERT INTO reviews (attraction_id, user_id, rating, comment) VALUES
(1, 1, 9, 'Amazing view!'),
(1, 2, 8, 'Crowded but worth it.'),
(2, 1, 7, 'Impressive scale.'),
(3, 3, 10, 'Unforgettable experience!'),
(4, 2, 6, 'Needs better preservation.'),
(5, 1, 9, 'Beautiful architecture.'),
(6, 3, 8, 'Breathtaking scenery.'),
(7, 2, 7, 'Iconic landmark.'),
(8, 1, 10, 'Inspirational.'),
(9, 3, 9, 'So much to see!'),
(10, 2, 8, 'Great photo opportunities.');