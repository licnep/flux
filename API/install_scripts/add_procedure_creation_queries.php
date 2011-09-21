<?php

array_push($queries,
    "DROP PROCEDURE IF EXISTS move_money;",
    "CREATE PROCEDURE move_money (from_id INT)
    #this procedures moves the money from a flux to the right recipients
    BEGIN # {
        DECLARE totalM DECIMAL(7,2); #the money to be moved
        DECLARE total INT; #the sum of all the shares
        #totalM is simply the amount of money to be moved, let's set it:
        SELECT money FROM fluxes
           WHERE flux_id = from_id
           INTO totalM;
        
        #now we set total to the sum of the shares coming from 'from_id'
        SELECT SUM(share) FROM routing
           WHERE flux_from_id = from_id
           GROUP BY flux_from_id 
           INTO total;

        #money movement has to be a transactions, either the 2 queries are both successfull, or they both fail
        START TRANSACTION; #{
           #remove money from the giver flux:
           UPDATE fluxes SET money = 0, last_update = NOW() WHERE flux_id = from_id;

           #put money in the receiver fluxes according to the percentages
           UPDATE fluxes AS f JOIN routing AS r ON f.flux_id = r.flux_to_id
             SET money=money+share*totalM/total
             WHERE r.flux_from_id = from_id;
        COMMIT; #commit the transaction

    END # }",
        
    "DROP PROCEDURE IF EXISTS update_least_updated",
    "
    #this procedure sends money from rows with at least 5$ and at least 1 second delay.
    #the maximum number of rows to update is passed as 'max'
    CREATE PROCEDURE update_least_updated (max INT)
    BEGIN # {
        DECLARE i INT;
        DECLARE id INT;

        SET i = 1;
        loopa: LOOP
            SELECT flux_id FROM fluxes 
            WHERE last_update < ( NOW() - INTERVAL 1 SECOND ) AND money > 2 
            ORDER BY last_update LIMIT 1 
            INTO id;
            IF id = NULL 
               THEN 
                 select \"id=null!\" as ctest_text;
                 LEAVE loopa; #leave if all fluxes are updated recently enough or with too little money to move
            END IF;
            CALL move_money(id);
                    SET i = i+1;
            IF i > max 
               THEN LEAVE loopa; #to exit the loop
            END IF;
        END LOOP;
    END # }",
        /*for tests:*/
        "insert into fluxes set flux_id=1,money=100,owner=0",
        "insert into fluxes set flux_id=5,money=5,owner=0",
        "insert into routing set flux_from_id=1,flux_to_id=5,share=7"
        
);
?>
