# SQL

![](images/sql-diagram.png)

## 1. Query

Based on the SQL diagram above, write the following queries:

**A.** Get authors with a last name beginning with "M" or who are born after 1950.

**Answer:**
```mysql
SELECT * 
FROM author 
WHERE last_name LIKE 'M%' OR YEAR(birth_date) > 1950;
```

**B.** Count the number of books per category (empty categories too).

**Answer:**
```mysql
SELECT category.id, COUNT(1) AS nb 
FROM category
LEFT JOIN book ON category.id = book.category_id
GROUP BY category.id;
```

**C.** Find authors who wrote at least 2 books.

**Answer:**
```mysql
SELECT author.* 
FROM author
INNER JOIN book ON author.id = book.author_id
GROUP BY author.id
HAVING COUNT(1) >= 2;
```

**D.** Get 50 authors with at least one event between the start and the end of this year.

**Answer:**
```mysql
SELECT author.* 
FROM author
INNER JOIN author_event ON author_event.author_id = author.id
INNER JOIN event ON event.id = author_event.event_id
WHERE YEAR(event.date) = YEAR(NOW())
GROUP BY author.id
LIMIT 0,50;
```

**E.** Get the average number of books written by authors.

**Answer:**
```mysql
SELECT AVG(t.nb) AS mean
FROM (
  SELECT COUNT(1) AS nb
  FROM author
  LEFT JOIN book ON author.id = book.author_id
  GROUP BY author.id
) AS t;
```

**F.** Get authors, sorted by the date of their **latest** event.

**Answer:**
```mysql
SELECT author.*, t.event_date 
FROM author 
INNER JOIN (
  WITH ranked_event AS (
    SELECT author_event.author_id, event.date AS event_date, ROW_NUMBER() OVER (PARTITION BY author_event.author_id ORDER BY event.date DESC) AS rn
    FROM author_event 
    INNER JOIN event ON event.id = author_event.event_id
  )
  SELECT *
  FROM ranked_event 
  WHERE rn = 1
) AS t ON t.author_id = author.id
ORDER BY t.event_date DESC
```

## 2. Database Structure

**A.** Based on the SQL diagram above, what can be done to improve the performance of this query ?

```mysql
SELECT id, name FROM book WHERE YEAR(published_date) >= '1973';
```

**Answer:** Add an index to the published_date column :
```mysql 
ALTER TABLE book ADD INDEX `idx_published_date` (published_date);
```


**B.** Give 3 common good practice on a database structure to optimize queries.

**Answer:** 
 - Index columns that will be used in WHERE a lot (almost if a the column have a high cardinality)
 - Use INNODB engine in operationnal database (write uses and integrity needed), MyISAM in datawarehouse (read use)
 - Use appropriate datatypes. For example: CHAR(5) for a French postal code, TINYINT for human age...
