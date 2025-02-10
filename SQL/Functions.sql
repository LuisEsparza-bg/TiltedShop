-- Función para agregar un criterio de ordenamiento a la cláusula ORDER BY
DELIMITER //
CREATE FUNCTION AddOrderByCriterion(
    currentOrderBy VARCHAR(1000),
    criterion VARCHAR(255),
    sortOrder INT,
    useFilter BOOLEAN
)
RETURNS VARCHAR(1000)
DETERMINISTIC
BEGIN
    DECLARE result VARCHAR(1000);

    IF useFilter THEN
        IF currentOrderBy = '' THEN
            -- Si es el primer criterio, no agregar coma
            SET result = CONCAT(criterion, ' ', CASE WHEN sortOrder = 0 THEN 'ASC' ELSE 'DESC' END);
        ELSE
            -- Si no es el primer criterio, agregar coma
            SET result = CONCAT(currentOrderBy, ', ', criterion, ' ', CASE WHEN sortOrder = 0 THEN 'ASC' ELSE 'DESC' END);
        END IF;
    ELSE
        -- No usar el filtro
        SET result = currentOrderBy;
    END IF;

    RETURN result;
END //
DELIMITER ;