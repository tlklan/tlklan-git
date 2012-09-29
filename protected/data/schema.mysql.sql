#
# 2012-01-29
#
# Add modifierId column
ALTER TABLE `cms_content`  ADD COLUMN `modifierId` VARCHAR(255) NULL DEFAULT NULL AFTER `heading`;