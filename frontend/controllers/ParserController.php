<?php
namespace frontend\controllers;

use yii\httpclient\client;
use yii\web\Controller;

/**
 * Класс для парсинга страничек HTML
 * 
 * @author SadDinamo
 */
class ParserController extends Controller
{
    public $client;

    /**
     * Получает HTML страницы по ссылке
     * @return String
     */
    private function GetHtmlContentByLink ($link) {
        $client = new client (['baseUrl' => $link]);
        $response = $client->createRequest()->send();
        return $response->content;
    }

    /**
     * Получает двухуровневый массив с покупками инсайдеров (костыльненько)
     * 
     * array (size=***)
     *  =>
     * array (size=5)
     * 'ticker' => string
     * 'date' => string
     * 'value' => int
     * 'link' => string
     * 'secDate' => string
     * 
     */
    public function GetInsiderBuyHistoryArray () {
        $deals = $this->GetHtmlContentByLink('https://finviz.com/insidertrading.ashx?tv=100000&tc=1&o=Ticker');
        $cd = 'UTF-8';
        $ndl1 = 'onclick="if(ignoreOnClick==false)window.location=';
        $ndl2 = 'class="tab-link">';
        $ndl3 = 'style="white-space:nowrap"';
        $ndl4 = 'align="right"';
        $ndl5 = 'onclick = "ignoreOnClick=true;"';
        $dealsArray = array();
        $dealsSubArray = array();
        // Проверка на пустую таблицу
        if (mb_stripos($deals, $ndl1, 0, $cd)) {
            // Позиция строки
            $pos = mb_stripos($deals, $ndl1, 0, $cd);
            $sub_deals = $deals;
            while ($pos > 0) {
                // Ticker
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl1, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, $ndl2, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl2, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '</a>', 0, $cd);
                $ticker = mb_substr($sub_deals, 0, $pos, $cd);
                $dealsSubArray['ticker'] = $ticker;
                // Owner
                $pos = mb_stripos($sub_deals, $ndl3, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl3, $cd), NULL, $cd);
                // Relationship
                $pos = mb_stripos($sub_deals, $ndl3, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl3, $cd), NULL, $cd);
                // Date
                $pos = mb_stripos($sub_deals, $ndl3, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl3, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '</td>', 0, $cd);
                $date = mb_substr($sub_deals, 1, $pos - 1, $cd);
                $dealsSubArray['date'] = $date;
                // Translation
                $pos = mb_stripos($sub_deals, $ndl3, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl3, $cd), NULL, $cd);
                // Cost
                $pos = mb_stripos($sub_deals, $ndl4, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl4, $cd), NULL, $cd);
                // Shares
                $pos = mb_stripos($sub_deals, $ndl4, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl4, $cd), NULL, $cd);
                // Value
                $pos = mb_stripos($sub_deals, $ndl4, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl4, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '</td>', 0, $cd);
                $value = mb_substr($sub_deals, 1, $pos - 1, $cd);
                $value = intval(str_replace(',', '', $value));
                $dealsSubArray['value'] = $value;
                // Shares total
                $pos = mb_stripos($sub_deals, $ndl4, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl4, $cd), NULL, $cd);
                // SEC form 4: link & date
                $pos = mb_stripos($sub_deals, $ndl5, 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen($ndl5, $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '<a href="', 0, $cd);
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen('<a href="', $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '" class="tab-link"', 0, $cd);
                $link = mb_substr($sub_deals, 0, $pos, $cd);
                $dealsSubArray['link'] = $link;
                $sub_deals = mb_substr($sub_deals, $pos + mb_strlen('" class="tab-link"', $cd), NULL, $cd);
                $pos = mb_stripos($sub_deals, '</a>', 0, $cd);
                $secDate = mb_substr($sub_deals, 1, $pos - 1, $cd);
                $dealsSubArray['secDate'] = $secDate;
                // Следующая строка таблицы
                $pos = mb_stripos($sub_deals, $ndl1, 0, $cd);
                array_push($dealsArray, $dealsSubArray);
                $dealsSubArray = array();
            }
        }
        return ($dealsArray);
    }

    /**
     * Получение многомерного массива тикеров маржинальной торговли
     * @return Array
     */
    public function GetMarginSharesArray () {
        $html = $this->GetHtmlContentByLink('https://www.tinkoff.ru/invest/margin/equities/');
        $cd = 'UTF-8';
        $node = 'uikit/table.tableRow';
        $tickersArray = array();
        $tickersSubArray = array();
        $pos = mb_strpos($html, $node, 0, $cd);
        if($pos) {
            $node = 'tbody';
            $pos = mb_strpos($html, $node, 0, $cd);
            $html = mb_substr($html, $pos + mb_strlen($node, $cd), NULL, $cd);
            while ($pos>0) {
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
                if ($pos<10) {
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
                if ($tickersSubArray['type']==='Акции' AND $tickersSubArray['short']==='Доступен') {
                    array_push($tickersArray, $tickersSubArray);
                }
                $tickersSubArray = array();
            }
        }
        return ($tickersArray);
    }

    /**
     * Var_dump отчет с многомерным массивом тикеров маржинальной торговли
     */
    public function actionMarginsharesarray () {
        $MarginSharesArray = SELF::GetMarginSharesArray();
        return $this->render('marginshares', ['a' => $MarginSharesArray]);
    }

    /**
     * Вовращает первую строку между массивом начальных строк и конечной строкой (регистронезависимо)
     * 
     * @param string $content Строка, в которой производится поиск
     * @param array $beginString Строка, после которой начинается искомая строка
     * @param string $endString Строка, начиная с которой закансивается искомая строка
     * 
     * @return string
     */
    private function HtmlGetString($content, $beginString, $endString) {
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
     * Возвращает массив с данными по тикеру с сайта Yahoo.finance
     * 
     * @param string $ticker Тикер, для которого будет возвращен массив с данными
     * 
     * @return array
     */
    private function yahooTickerInfo($ticker) {
        $result = array();
        $result['Ticker'] = $ticker;
        $content = $this->GetHtmlContentByLink('https://finance.yahoo.com/quote/'.$ticker.'/key-statistics?p='.$ticker);
        // Fiscal Year Ends
            $beginString = array('Fiscal Year Ends</span>', 'data-reactid="', 'data-reactid="', '">');
            $endString = '</td></tr>';
            $result['Fiscal Year Ends'] = date('Y-m-d H:i:s', strtotime($this->HtmlGetString($content, $beginString, $endString)));
        // Most Recent Quarter (mrq)
            $beginString = array('Most Recent Quarter</span>', 'data-reactid="', 'data-reactid="', '">');
            $endString = '</td></tr>';
            $result['Most Recent Quarter (mrq)'] = date('Y-m-d H:i:s', strtotime($this->HtmlGetString($content, $beginString, $endString)));
        // Total Cash (mrq)
            $beginString = array('Total Cash</span>', 'data-reactid="', 'data-reactid="', '">');
            $endString = '</td></tr>';
            $result['Total Cash (mrq)'] = array('value' => $this->HtmlGetString($content, $beginString, $endString), 'date' => $result['Most Recent Quarter (mrq)']);
        // Total Debt (mrq)
            $beginString = array('Total debt</span>', 'data-reactid="', 'data-reactid="', '">');
            $endString = '</td></tr>';
            $result['Total Debt (mrq)'] = array('value' => $this->HtmlGetString($content, $beginString, $endString), 'date' => $result['Most Recent Quarter (mrq)']);
        // Short Ratio
            $beginString = array('Short Ratio (');
            $endString = ')';
            $date = date('Y-m-d H:i:s', strtotime($this->HtmlGetString($content, $beginString, $endString)));
            $beginString = array('Short Ratio ', 'data - reactid = "', 'data-reactid="', 'data-reactid="', '">');
            $endString = '</td></tr>';
            $result['Short Ratio'] = array('value' => $this->HtmlGetString($content, $beginString, $endString), 'date'=>$date);
        // Short % of Float
            $beginString = array('Short % of Float (');
            $endString = ')';
            $date = date('Y-m-d H:i:s', strtotime($this->HtmlGetString($content, $beginString, $endString)));
            $beginString = array('Short % of Float ', 'data - reactid = "', 'data-reactid="', 'data-reactid="', '">');
            $endString = '</td></tr>';
            $result['Short % of Float'] = array('value' => $this->HtmlGetString($content, $beginString, $endString), 'date' => $date);
        // Operating Cash Flow (Trailing Twelve Months)
            $beginString = array('Operating Cash Flow', 'data-reactid="', 'data-reactid="', '">');
            $endString = '</td></tr>';
            $result['Operating Cash Flow (Trailing Twelve Months)'] = array('value' => $this->HtmlGetString($content, $beginString, $endString), 'date' => date('Y-m-d H:i:s', strtotime('today')));
        return $result;
    }

    /**
     * Var_dump отчет с информацией по тикеру с yahoo.finance
     * 
     */
    public function actionYahootickerinfo($ticker = 'FIZZ'){
        $a = $this-> yahooTickerInfo($ticker);
        return $this->render('yahootickerinfo', ['a' => $a]);
    }
}