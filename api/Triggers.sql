DELIMITER $$
CREATE TRIGGER after_qlc_validation_request_update 
    AFTER UPDATE ON qlc_validation_request
    FOR EACH ROW 
BEGIN
    UPDATE form_encounter
    SET request = NEW.request
    WHERE encounter = OLD.encounter_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_qlc_validation_request_insert 
    AFTER INSERT ON qlc_validation_request
    FOR EACH ROW 
BEGIN
    UPDATE form_encounter
    SET request = NEW.request
    WHERE encounter = NEW.encounter_id; 
END$$
DELIMITER ;