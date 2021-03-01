<?php

namespace frontend\models;

use yii\base\Model;
use yii\httpclient\client;
use yii\helpers\BaseJson;
use yii\helpers\ArrayHelper;

/**
 *  Модель для получения данных с различных сайтов
 * 
 */
class Parser extends Model
{
    /**
     * Получает HTML кода страницы по ссылке
     * @return String
     */
    private function GetHtmlContentByLink($link)
    {
        $client = new client(['baseUrl' => $link]);
        $response = $client->createRequest()->send();
        return $response->content;
    }

    /**
     * Вовращает первую строку между массивом начальных строк и конечной строкой (регистронезависимо)
     * 
     * @param string $content Строка, в которой производится поиск
     * @param array $beginString Массив строк, после которых начинается искомая строка
     * @param string $endString Строка, начиная с которой заканчивается искомая строка
     * 
     * @return string
     */
    private function HtmlGetString($content, $beginString, $endString)
    {
        $cd = 'UTF-8';
        $pos = mb_stripos($content, $beginString[0], 0, $cd);
        if ($pos) {
            foreach ($beginString as $aString) {
                $pos = mb_stripos($content, $aString, 0, $cd);
                $content = mb_substr($content, $pos + mb_strlen($aString, $cd), NULL, $cd);
            }
            $pos = mb_stripos($content, $endString, 0, $cd);
            if ($pos) {
                $content = mb_substr($content, 0, $pos, $cd);
                return $content;
            }
            return ('No string found');
        }
        return ('No string found');
    }

    /**
     * Форматирует строку парсинга сайтов и возвращает ее в типе float
     * 
     * @param string
     * 
     * @return float
     */
    private function StrToFloat ($string) {
        $cd = 'UTF-8';
        switch (mb_substr($string, strlen($string) - 1, 1, $cd)) {
            case 'M':
            $result = floatval(mb_substr($string, 0, strlen($string) - 1, $cd)) * 1000000;
            break;
            case 'B':
            $result = floatval(mb_substr($string, 0, strlen($string) - 1, $cd)) * 1000000000;
            break;
            case 'K':
            $result = floatval(mb_substr($string, 0, strlen($string) - 1, $cd)) * 1000;
            break;
            case '%':
            $result = floatval(mb_substr($string, 0, strlen($string) - 1, $cd)) / 100;
            break;
            default:
            $result = floatval($string);
        }
        return $result;
    }

    /**
     * Возвращает массив с данными по тикеру с сайта Yahoo.finance
     * 
     * @param string $ticker Тикер, для которого будет возвращен массив с данными
     * 
     * @return array
     */
    public static function yahooTickerInfo($ticker){
        $result = array();
        $cd = 'UTF-8';
        $content = SELF::GetHtmlContentByLink('https://finance.yahoo.com/quote/' . $ticker . '/key-statistics?p=' . $ticker);
        if (mb_stripos($content, 'All (0)', 0, $cd)) {
            $result['Found'] = FALSE;
        } else {
            $result['Found'] = TRUE;
        };
        $result['Ticker'] = $ticker;
        // Fiscal Year Ends
        $beginString = array('Fiscal Year Ends</span>', 'data-reactid="', 'data-reactid="', '">');
        $endString = '</td></tr>';
        $result['Fiscal Year Ends'] = date('Y-m-d H:i:s', strtotime(SELF::HtmlGetString($content, $beginString, $endString)));
        // Most Recent Quarter (mrq)
        $beginString = array('Most Recent Quarter</span>', 'data-reactid="', 'data-reactid="', '">');
        $endString = '</td></tr>';
        $result['Most Recent Quarter (mrq)'] = date('Y-m-d H:i:s', strtotime(SELF::HtmlGetString($content, $beginString, $endString)));
        // Total Cash (mrq)
        $beginString = array('Total Cash</span>', 'data-reactid="', 'data-reactid="', '">');
        $endString = '</td></tr>';
        $result['Total Cash (mrq)'] = array('value' => SELF::StrToFloat(SELF::HtmlGetString($content, $beginString, $endString)), 'date' => $result['Most Recent Quarter (mrq)']);
        // Total Debt (mrq)
        $beginString = array('Total debt</span>', 'data-reactid="', 'data-reactid="', '">');
        $endString = '</td></tr>';
        $result['Total Debt (mrq)'] = array('value' => SELF::StrToFloat(SELF::HtmlGetString($content, $beginString, $endString)), 'date' => $result['Most Recent Quarter (mrq)']);
        // Short Ratio
        $beginString = array('Short Ratio (');
        $endString = ')';
        $date = date('Y-m-d H:i:s', strtotime(SELF::HtmlGetString($content, $beginString, $endString)));
        $beginString = array('Short Ratio ', 'data - reactid = "', 'data-reactid="', 'data-reactid="', '">');
        $endString = '</td></tr>';
        $result['Short Ratio'] = array('value' => SELF::StrToFloat(SELF::HtmlGetString($content, $beginString, $endString)), 'date' => $date);
        // Short % of Float
        $beginString = array('Short % of Float (');
        $endString = ')';
        $date = date('Y-m-d H:i:s', strtotime(SELF::HtmlGetString($content, $beginString, $endString)));
        $beginString = array('Short % of Float ', 'data - reactid = "', 'data-reactid="', 'data-reactid="', '">');
        $endString = '</td></tr>';
        $result['Short % of Float'] = array('value' => SELF::StrToFloat(SELF::HtmlGetString($content, $beginString, $endString)), 'date' => $date);
        // Operating Cash Flow (Trailing Twelve Months)
        $beginString = array('Operating Cash Flow', 'data-reactid="', 'data-reactid="', '">');
        $endString = '</td></tr>';
        $result['Operating Cash Flow (Trailing Twelve Months)'] = array('value' => SELF::StrToFloat(SELF::HtmlGetString($content, $beginString, $endString)), 'date' => date('Y-m-d H:i:s', strtotime('today')));
        return $result;
    }


    /**
     * Возвращает массив с данными JSON запроса по тикеру с сайта use yii\base\UnknownMethodException;
     * 
     * @param string $ticker
     * 
     * @return array
     * 
     */
    public static function getYahooTickerJSON ($ticker) {
        $content = SELF::GetHtmlContentByLink('https://query1.finance.yahoo.com/v10/finance/quoteSummary/'.$ticker.'?modules=price,defaultKeyStatistics');
        $result = BaseJson::decode($content);
        $result = ArrayHelper::getValue($result, 'quoteSummary');
        $result = ArrayHelper::getValue($result, 'result');
        $result = ArrayHelper::getValue($result, 0);
        return $result;
    }

    /**
     * Получение многомерного массива тикеров маржинальной торговли
     * @return Array
     */
    public static function GetMarginSharesArray() //TODO::переписать с использованием SELF::HtmlGetString ???
    {
        $html = SELF::GetHtmlContentByLink('https://www.tinkoff.ru/invest/margin/equities/');
        $cd = 'UTF-8';
        $node = 'uikit/table.tableRow';
        $tickersArray = array();
        $tickersSubArray = array();
        $pos = mb_strpos($html, $node, 0, $cd);
        if ($pos) {
            $node = 'tbody';
            $pos = mb_strpos($html, $node, 0, $cd);
            $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
            while ($pos > 0) {
                // ticker
                $node = 'uikit/table.tableRow';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = 'class="LiquidPapersPure';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = 'data-qa-file="LiquidPapersPure">';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '<!--';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['ticker'] = $value;
                // Тип тикера
                $node = '<!-- -->';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '<!-- -->';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '</div></div></span></a></td><';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['type'] = $value;
                // ISIN
                $node = 'class="LiquidPapersPure__isin';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = 'data-qa-file="LiquidPapersPure">';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '</div></div></span></a></td><';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['isin'] = $value;
                // Доступен шорт
                $node = 'data-qa-file="Table">';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '</div></span></a></td><';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['short'] = $value;
                // Ставка риска в лонг
                $node = 'data-qa-file="Table">';
                $pos = mb_strpos($html, $node, 0, $cd);
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '<!-- -->';
                $pos = mb_strpos($html, $node, 0, $cd);
                $value = mb_substr($html, 0, $pos, $cd);
                $tickersSubArray['longRisk'] = $value;
                // Ставка риска в шорт
                $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                $node = '<!-- -->';
                $pos = mb_strpos($html, $node, 0, $cd);
                if ($pos < 10) {
                    $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
                    $node = '</div></span></a></td></tr>';
                    $pos = mb_strpos($html, $node, 0, $cd);
                    $value = mb_substr($html, 0, $pos, $cd);
                } else {
                    $value = NULL;
                }
                $tickersSubArray['shortRisk'] = $value;

                // Следующая строка таблицы
                $node = 'data-qa-type="uikit/table.tableRow"';
                $pos = mb_strpos($html, $node, 0, $cd);
                if ($tickersSubArray['type'] === 'Акции' and $tickersSubArray['short'] === 'Доступен') {
                    array_push($tickersArray, $tickersSubArray);
                }
                $tickersSubArray = array();
            }
        }
        return ($tickersArray);
    }
}