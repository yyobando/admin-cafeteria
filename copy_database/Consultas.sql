USE cafeteria;
/* MAYOR STOCK */
SELECT t1.id, t1.name, t1.stock  FROM product AS t1  WHERE t1.stock =  (SELECT MAX(stock) FROM product);
/* PRODUCTO MAS VENDIDO  */
SELECT t1.id, t1.name, SUM(t2.amount) AS amount_uni 
FROM product AS t1
INNER JOIN sale AS t2 ON t1.id = t2.id_product
GROUP BY t1.id
ORDER BY SUM(t2.amount) DESC LIMIT 1;   
