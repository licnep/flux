
DROP PROCEDURE IF EXISTS move_money; #drop the procedure before creating it, just to be sure
DELIMITER // #change the delimiter

CREATE PROCEDURE move_money (from_id INT)
#this procedures moves the money from a flux to the right recipients
BEGIN # {
    DECLARE total INT; # declare a variable total (type INT)
    #now we set total to the sum of the shares coming from 'from_id' (usually 100)
    SELECT money_waiting FROM fluxes
       WHERE flux_id = from_id
       INTO total;

    #money movement has to be a transactions, either the 2 queries are both successfull, or they both fail
    START TRANSACTION; #{
       #remove money from the giver flux:
       UPDATE fluxes SET money_waiting = 0, last_update = NOW() WHERE flux_id = from_id;

       #put money in the receiver fluxes according to the percentages
       UPDATE fluxes AS f JOIN routing AS r ON f.flux_id = r.flux_to_id
         SET money_waiting=money_waiting+share*total/100
         WHERE r.flux_from_id = from_id;
    COMMIT; #commit the transaction

END// # }

DELIMITER ;
DROP PROCEDURE IF EXISTS update_least_updated;
DELIMITER //
#this procedure sends money from rows with at least 5$ and at least 1 second delay.
#the maximum number of rows to update is passed as 'max'
CREATE PROCEDURE update_least_updated (max INT)
BEGIN # {
    DECLARE i INT;
    DECLARE id INT;

    SET i = 1;
    loopa: LOOP
        SELECT flux_id FROM fluxes 
        WHERE last_update < ( NOW() - INTERVAL 1 SECOND ) AND money_waiting > 5 
        ORDER BY last_update LIMIT 1 
        INTO id;
        IF id = NULL 
           THEN 
             select "id=null!" as ctest_text;
             LEAVE loopa; #leave if all fluxes are updated enough and with too little money to move
        END IF;
        CALL move_money(id);
		SET i = i+1;
        IF i > max 
           THEN LEAVE loopa; #to exit the loop
        END IF;
    END LOOP;
END// # }

DELIMITER ;
