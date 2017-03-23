<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test;

use org\bovigo\vfs\vfsStream;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $fsRoot;

    protected function setUp()
    {
        $this->fsRoot = vfsStream::setup('root');
    }

    /**
     * @param string $fileName
     * @param int $mode
     * @return string
     */
    protected function createFileMock($fileName, $mode = 0644)
    {
        $fileMock = vfsStream::newFile($fileName)
            ->at($this->fsRoot)
            ->chmod($mode);
        return $fileMock->url();
    }

    /**
     * @param string $fileName
     * @param array $csvData
     * @return string
     */
    protected function mockCsvFile($fileName, array $csvData)
    {
        $csvFileMockPath = $this->createFileMock($fileName);
        $handle = fopen($csvFileMockPath, 'w');
        if (false !== $handle) {
            foreach ($csvData as $csvRow) {
                fputcsv($handle, $csvRow);
            }
        }
        fclose($handle);
        return $csvFileMockPath;
    }

    /**
     * @param string $filePath
     * @return string
     */
    protected function mockCsvFileFromFilesystem($filePath)
    {
        $fileName = basename($filePath);
        $csvFileMockPath = $this->createFileMock($fileName);
        file_put_contents($csvFileMockPath, file_get_contents($filePath));
        return $csvFileMockPath;
    }

    /**
     * @param string $fileName
     * @return string
     */
    protected function getFixtureFilePath($fileName)
    {
        return realpath(join(DIRECTORY_SEPARATOR, [__DIR__, '_files', $fileName]));
    }

    /**
     * @param string $filePath
     * @param bool $sort
     * @return array
     */
    protected function readCsvData($filePath, $sort = false)
    {
        $handle = fopen($filePath, 'r');
        if (false !== $handle) {
            $data = [];
            while (false !== ($row = fgetcsv($handle))) {
                $data[] = $row;
            }
            if ($sort) {
                sort($data);
            }
            return $data;
        }
        return [];
    }

    /**
     * @return array
     */
    public function dataArrayProvider()
    {
        return [
            [
                'simple_string_test.csv',
                [
                    ['Lorem ipsum dolor sit amet', 'consectetur adipiscing elit', 'Vestibulum et urna eros'],
                    ['Quisque id magna nec ipsum imperdiet pellentesque'],
                    ['Duis quis tellus sit amet urna facilisis pretium', 'Maecenas at varius ex'],
                    ['Praesent aliquet eleifend interdum', 'Orci varius natoque penatibus et magnis dis parturient'],
                    ['nascetur', 'ridiculus', 'mus', 'Integer', 'vel', 'neque', 'dui']
                ]
            ],
            [
                'utf8_string_test.csv',
                [
                    ['Litwo! Ojczyzno moja!', 'Ty jesteś jak zdrowie.'],
                    ['Ile cię trzeba cenić, ten tylko się dowie, kto cię stracił!'],
                    ['Drogi Marszałku,', 'Wysoka Izbo.', 'PKB rośnie.'],
                    ['żył', 'w Polsce', 'łaskawy', 'sprawiedliwy']
                ]
            ],
            [
                'cyrillic_string_test.csv',
                [
                    ['Алексей Федорович Карамазов', 'был чрезвычайно вежливым'],
                    ['почти бежал сбоку,', 'рассматривая их детства', 'запуганною молодою женщиной'],
                    ['произошло бы что-то', 'как беспрекословно и предупредительнее'],
                    ['чем', 'просто', 'не', 'имеет', 'некоторое', 'время', 'да', 'не', 'знаю', 'про', 'себя']
                ]
            ],
            [
                'numeric_test.csv',
                [
                    [3.14159265359, 2.71828182846],
                    [0, 1, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89],
                    [2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97]
                ]
            ],
            [
                'mixed_data_test.csv',
                [
                    ['Lorem ipsum dolor sit amet', 'consectetur adipiscing elit', 'Vestibulum et urna eros'],
                    [3.14159265359, 2.71828182846],
                    ['чем', 'просто', 'не', 'имеет', 'некоторое', 'время', 'да', 'не', 'знаю', 'про', 'себя'],
                    [0, 1, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89],
                    ['Ile cię trzeba cenić, ten tylko się dowie, kto cię stracił!', '""'],
                    ['Watch "Jeopardy!" $$$', "!%& /() =?* '<> #|; ²³~ @`´ ©«» ¤¼× {}"]
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function filesForMergeProvider()
    {
        return [
            ['simple_left.csv', 'simple_right.csv', 'simple_sorted.csv'],
            ['utf8_left.csv', 'utf8_right.csv', 'utf8_sorted.csv'],
            ['different_left.csv', 'different_right.csv', 'different_sorted.csv'],
            ['quirk_left.csv', 'quirk_right.csv', 'quirk_sorted.csv']
        ];
    }

    /**
     * @return array
     */
    public function filesForMergeAndSanitizeProvider()
    {
        return [
            ['simple_left.csv', 'simple_right.csv', 'simple_sanitized.csv'],
            ['utf8_left.csv', 'utf8_right.csv', 'utf8_sanitized.csv'],
            ['different_left.csv', 'different_right.csv', 'different_sanitized.csv'],
            ['quirk_left.csv', 'quirk_right.csv', 'quirk_sanitized.csv']
        ];
    }

    /**
     * @return array
     */
    public function filesForSortProvider()
    {
        return [
            ['simple_merged.csv', 'simple_sorted.csv'],
            ['utf8_merged.csv', 'utf8_sorted.csv'],
            ['different_merged.csv', 'different_sorted.csv'],
            ['quirk_merged.csv', 'quirk_sorted.csv']
        ];
    }

    /**
     * @return array
     */
    public function filesForSanitizeProvider()
    {
        return [
            ['simple_merged.csv', 'simple_sanitized.csv'],
            ['utf8_merged.csv', 'utf8_sanitized.csv'],
            ['different_merged.csv', 'different_sanitized.csv'],
            ['quirk_merged.csv', 'quirk_sanitized.csv']
        ];
    }
}
