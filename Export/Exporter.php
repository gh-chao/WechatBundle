<?php

namespace Lilocon\WechatBundle\Export;

use Exporter\Source\SourceIteratorInterface;
use Exporter\Writer\CsvWriter;
use Exporter\Writer\JsonWriter;
use Exporter\Writer\XlsWriter;
use Exporter\Writer\XmlWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Exporter
{
    /**
     * @var SourceIteratorInterface
     */
    private $source;

    /**
     * Exporter constructor.
     * @param SourceIteratorInterface $source
     */
    public function __construct(SourceIteratorInterface $source)
    {
        $this->source = $source;
    }

    public function getResponse($format, $filename)
    {
        switch ($format) {
            case 'xls':
                $writer      = new XlsWriter('php://output');
                $contentType = 'application/vnd.ms-excel';
                break;
            case 'xml':
                $writer      = new XmlWriter('php://output');
                $contentType = 'text/xml';
                break;
            case 'json':
                $writer      = new JsonWriter('php://output');
                $contentType = 'application/json';
                break;
            case 'csv':
                $writer      = new CsvWriter('php://output', ',', '"', '', true, true);
                $contentType = 'text/csv';
                break;
            default:
                throw new \RuntimeException('Invalid format');
        }

        $source = $this->source;

        $callback = function () use ($source, $writer) {
            $handler = \Exporter\Handler::create($source, $writer);
            $handler->export();
        };

        return new StreamedResponse(
            $callback, 200, array(
                'Content-Type'        => $contentType,
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            )
        );
    }

    public function export($format, $output)
    {
        switch ($format) {
            case 'xls':
                $writer = new XlsWriter($output);
                break;
            case 'xml':
                $writer = new XmlWriter($output);
                break;
            case 'json':
                $writer = new JsonWriter($output);
                break;
            case 'csv':
                $writer = new CsvWriter($output, ',', '"', '', true, true);
                break;
            default:
                throw new \RuntimeException('Invalid format');
        }
        $handler = \Exporter\Handler::create($this->source, $writer);
        $handler->export();
    }

}