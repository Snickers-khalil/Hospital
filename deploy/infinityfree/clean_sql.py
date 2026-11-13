"""Make the MariaDB dump importable on InfinityFree:
- removes triggers (not allowed on free hosting; the PHP code already updates stock)
- removes DEFINER clauses (the host user is not root)
- removes CREATE DATABASE / USE lines (the database already exists there)
"""
import re, sys

src, dst = sys.argv[1], sys.argv[2]
sql = open(src, encoding="utf-8").read()

before = len(re.findall(r"TRIGGER `", sql))
# mysqldump style: DELIMITER ;; ... CREATE ... TRIGGER ... END */;; DELIMITER ;
sql = re.sub(r"DELIMITER ;;\s*/\*!50003 CREATE\*/.*?TRIGGER.*?END \*/;;\s*DELIMITER ;\s*", "", sql, flags=re.S)
# phpMyAdmin style: DELIMITER $$ CREATE TRIGGER ... END $$ DELIMITER ;
sql = re.sub(r"DELIMITER \$\$\s*CREATE TRIGGER.*?END\s*\$\$\s*DELIMITER ;\s*", "", sql, flags=re.S)
sql = re.sub(r"/\*!50017 DEFINER=`[^`]*`@`[^`]*`\*/\s*", "", sql)
sql = re.sub(r"DEFINER=`[^`]*`@`[^`]*`\s*", "", sql)
sql = re.sub(r"^(CREATE DATABASE|USE) .*?;\s*$", "", sql, flags=re.M)

left = len(re.findall(r"TRIGGER `", sql))
if left:
    sys.exit(f"ERROR: {left} trigger(s) still in the dump")
open(dst, "w", encoding="utf-8").write(sql)
print(f"SQL cleaned: {before} triggers removed -> {dst}")
