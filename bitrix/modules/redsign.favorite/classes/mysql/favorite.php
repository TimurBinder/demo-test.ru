<?php

class CRSFavorite
{
    public const SESSION_CODE = 'RS_FAVORITE';

    public static function GetList(array $aSort = ['SORT' => 'ID'], array $aFilter = []): \CDBResult
    {
        global $DB;

        $arFilter = array();
        foreach ($aFilter as $key => $val) {
            $val = $DB->ForSql($val);
            if (strlen($val) <= 0)
                continue;
            switch (strtoupper($key)) {
                case "ID":
                    $arFilter[] = "RSF.ID='" . $val . "'";
                    break;
                case "FUSER_ID":
                    $arFilter[] = "RSF.FUSER_ID='" . $val . "'";
                    break;
                case "ELEMENT_ID":
                    $arFilter[] = "RSF.ELEMENT_ID='" . $val . "'";
                    break;
                case "PRODUCT_ID":
                    $arFilter[] = "RSF.PRODUCT_ID='" . $val . "'";
                    break;
            }
        }

        $arOrder = array();
        foreach ($aSort as $key => $val) {
            $ord = (strtoupper($val) <> "ASC" ? "DESC" : "ASC");
            switch (strtoupper($key)) {
                case "ID":
                    $arOrder[] = "RSF.ID " . $ord;
                    break;
                case "FUSER_ID":
                    $arOrder[] = "RSF.FUSER_ID " . $ord;
                    break;
                case "ELEMENT_ID":
                    $arOrder[] = "RSF.ELEMENT_ID " . $ord;
                    break;
                case "PRODUCT_ID":
                    $arOrder[] = "RSF.PRODUCT_ID " . $ord;
                    break;
            }
        }

        if (count($arOrder) == 0)
            $arOrder[] = "RSF.ID DESC";
        $sOrder = "\nORDER BY " . implode(", ", $arOrder);

        if (count($arFilter) == 0)
            $sFilter = "";
        else $sFilter = "\nWHERE " . implode("\nAND ", $arFilter);

        $strSql = "
			SELECT
			DISTINCT
				RSF.*
			FROM
				b_redsign_favorite RSF
			" . $sFilter . $sOrder;

        return $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
    }

    public static function GetByID(int $ID): \CDBResult
    {
        return CRSFavorite::Getlist(array(), array("ID" => $ID));
    }

    public static function Delete(int $ID): \CDBResult
    {
        global $DB;
        $ID = intval($ID);

        $DB->StartTransaction();

        $res = $DB->Query("DELETE FROM b_redsign_favorite WHERE ID=" . $ID, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
        if ($res) {
            $DB->Commit();
        } else {
            $DB->Rollback();
        }

        return $res;
    }

    /**
     * @param array $arFields
     * @return int|false
     */
    public static function Add(array $arFields)
    {
        global $DB;

        $ID = $DB->Add("b_redsign_favorite", $arFields);
        if (IntVal($ID) > 0)
            return $ID;
        else return false;
    }

    /**
     * @param int $ID
     * @param array $arFields
     * @return int|false
     */
    public static function Update(int $ID, array $arFields)
    {
        global $DB;

        if (isset($arFields["ID"]))
            unset($arFields["ID"]);

        $strUpdate = $DB->PrepareUpdate("b_redsign_favorite", $arFields);
        if ($strUpdate != '') {
            $strSql = "UPDATE b_redsign_favorite SET " . $strUpdate . " WHERE ID=" . $ID;
            $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
            return $ID;
        } else {
            return false;
        }
    }
}
