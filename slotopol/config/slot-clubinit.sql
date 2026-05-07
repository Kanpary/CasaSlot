-- CasaSlot: initialize default club (house) for slotopol game server
-- Schema: club(cid, ctime, utime, name, bank, fund, lock, rate, mrtp)
INSERT OR IGNORE INTO club (cid, name, bank, fund, lock, rate, mrtp) VALUES (1, 'CasaSlot', 1000000000, 0, 0, 2.5, 0);
