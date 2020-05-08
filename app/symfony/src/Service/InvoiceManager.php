<?php


namespace App\Service;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\Orders;
use App\Entity\Payment;
use App\Entity\Shop;
use JMS\Serializer\Tests\Fixtures\Order;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class InvoiceManager
{
    const INVOICE_NAME = 'facture';

    /** @var Environment */
    private $twig;

    /**
     * QuotationService constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generateInvoicePDF(Orders $order)
    {
        $filename = sprintf('%s_%s.pdf', self::INVOICE_NAME, $order->getOrderNumber());
        $tempDir = '/tmp';
        $fullPath = $tempDir.'/'.$filename;

        try {
            $mpdf = new Mpdf([
                'tempDir' => '/tmp',
                'margin_top' => 11,
                'margin_left' => 11,
                'margin_right' => 11,
                'margin_bottom' => 0,
                'setAutoTopMargin' => 'stretch',
                'setAutoBottomMargin' => 'stretch'
            ]);

            $html = $this->twig->render('common/confirmationTemplate.html.twig', [
                'order' => $order,
                'card' =>$order->getCard()->first()

            ]);
            $header = ''; //$this->twig->render(null);
            $footer = '';//($this->twig->render(null));

            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);
            $mpdf->WriteHTML($html);
            $mpdf->Output($fullPath, Destination::FILE);
            $response = new Response();
            $response->setStatusCode(200);
            $response->setContent(file_get_contents($fullPath));
            $response->headers->set('Content-type', 'application/force-download');
            $response->headers->set('Content-Transfer-Encoding', 'binary');
            $response->headers->set('Content-Length', filesize($fullPath));
            $response->headers->set('Content-disposition', 'attachment; filename='.basename($fullPath));
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');

            unlink($fullPath);

            return $response;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
