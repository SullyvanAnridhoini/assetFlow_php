USE `assetflow_db`;

ALTER TABLE `assets`
  ADD COLUMN `motif_rebut` VARCHAR(255) NULL
  AFTER `statut`;

UPDATE `assets`
SET `motif_rebut` = 'Carte mère hors service suite à court-circuit (déclassé)'
WHERE `code_inventaire` = 'LAP-2020-088';