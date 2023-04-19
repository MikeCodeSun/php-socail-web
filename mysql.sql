CREATE TABLE users(
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE post(
  id SERIAL PRIMARY KEY,
  content VARCHAR(255) NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  CONSTRAINT FK_UserPosr FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE follow(
  id SERIAL PRIMARY KEY,
  follow BIGINT UNSIGNED NOT NULL,
  followed_by BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  CONSTRAINT FK_Follow FOREIGN KEY (follow) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT FK_Followed FOREIGN KEY (followed_by) REFERENCES users(id) ON DELETE CASCADE
);

-- select post and user 
SELECT p.*, u.id as uid, u.name as uname FROM post p LEFT JOIN users u ON p.user_id = u.id WHERE p.user_id in (SELECT f.follow FROM follow f WHERE f.followed_by = ?);
-- add image column to users table
ALTER TABLE users ADD image VARCHAR(255);