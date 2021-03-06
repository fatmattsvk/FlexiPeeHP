<?php

namespace Test\FlexiPeeHP;

use FlexiPeeHP\FlexiBeeRO;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-05-04 at 10:08:36.
 */
class FlexiBeeROTest extends \Test\Ease\SandTest
{
    /**
     * @var FlexiBeeRO
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * @covers FlexiPeeHP\FlexiBeeRO::__construct
     */
    protected function setUp()
    {
        $this->object          = new FlexiBeeRO();
        $this->object->prefix  = '';
        $this->object->company = '';
        $this->object->debug   = true;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::logBanner
     */
    public function testLogBanner()
    {
        $this->object->logBanner(addslashes(get_class($this)));
    }

    /**
     * Test Constructor
     *
     * @depends testLogBanner
     * @covers FlexiPeeHP\FlexiBeeRO::__construct
     */
    public function testConstructor()
    {
        $classname = get_class($this->object);
        $evidence  = $this->object->getEvidence();

        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $mock->__construct(1, ['debug' => false]);

        if (!isset(\FlexiPeeHP\EvidenceList::$name[$evidence])) {
            $evidence = 'adresar';
        }

        $mock->__construct('',
            [
                'company' => constant('FLEXIBEE_COMPANY'),
                'url' => constant('FLEXIBEE_URL'),
                'user' => constant('FLEXIBEE_LOGIN'),
                'password' => constant('FLEXIBEE_PASSWORD'),
                'debug' => true,
                'prefix' => 'c',
                'evidence' => $evidence]);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::curlInit
     */
    public function testSetupProperty()
    {
        $properties = ['debug' => true];
        $this->object->setupProperty($properties, 'debug');
        $this->assertTrue($this->object->debug);
        $this->object->setupProperty($properties, 'url', 'FLEXIBEE_URL');
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::curlInit
     */
    public function testCurlInit()
    {
        $this->object->curlInit();
        $this->assertTrue(is_resource($this->object->curl));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::processInit
     */
    public function testProcessInit()
    {
        $this->object->processInit(['id' => 1]);
        $this->assertEquals(1, $this->object->getDataValue('id'));

        if (!is_null($this->object->evidence) && $this->object->evidence != 'test') {


            $firstID = $this->object->getColumnsFromFlexibee(['id', 'kod'],
                ['limit' => 1]);

            if (count($firstID) && isset($firstID[0]['id'])) {

                $this->object->processInit((int) current($firstID));
                $this->assertNotEmpty($this->object->__toString());

                if (isset($firstID[0]['kod'])) {
                    $this->object->processInit('code:'.$firstID[0]['kod']);
                    $this->assertNotEmpty($this->object->__toString());
                }

                $this->object->processInit($this->object->getEvidenceURL().'/'.$firstID[0]['id'].'.xml');
            } else {
                $this->markTestSkipped(sprintf('Evidence %s doed not contain first record',
                        $this->object->getEvidence()));
            }
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::setUp
     */
    public function testSetUp()
    {
        $this->object->setUp(
            [
                'company' => 'cmp',
                'url' => 'url',
                'user' => 'usr',
                'password' => 'pwd',
                'prefix' => 'c',
                'debug' => true,
                'defaultUrlParams' => ['limit' => 10],
                'evidence' => 'smlouva'
            ]
        );
        $this->assertEquals('cmp', $this->object->company);
        $this->assertEquals('url', $this->object->url);
        $this->assertEquals('usr', $this->object->user);
        $this->assertEquals('/c/', $this->object->prefix);
        $this->assertEquals('pwd', $this->object->password);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getConnectionOptions
     */
    public function testGetConnectionOptions(){
        $options = $this->object->getConnectionOptions();
        $this->assertArrayHasKey('url', $options);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::setPrefix
     * @expectedException \Exception
     */
    public function testSetPrefix()
    {
        $this->object->setPrefix('c');
        $this->assertEquals('/c/', $this->object->prefix);
        $this->object->setPrefix(null);
        $this->assertEquals('', $this->object->prefix);
        $this->object->setPrefix('fail');
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::setFormat
     */
    public function testSetFormat()
    {
        $this->object->setFormat('xml');
        $this->assertEquals('xml', $this->object->format);
    }

    /**
     * We can set only evidence defined in EvidenceList class
     *
     * @covers FlexiPeeHP\FlexiBeeRO::setEvidence
     * @expectedException \Exception
     */
    public function testSetEvidence()
    {
        $this->object->setEvidence('adresar');
        $this->assertEquals('adresar', $this->object->evidence);
        $this->object->setPrefix('c');
        $this->object->debug = true;
        $this->object->setEvidence('fail');
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::setCompany
     */
    public function testSetCompany()
    {
        $this->object->setCompany('test_s_r_o_');
        $this->assertEquals('test_s_r_o_', $this->object->company);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::object2array
     */
    public function testObject2array()
    {
        $this->assertNull($this->object->object2array(new \stdClass()));
        $this->assertEquals(
            [
                'item' => 1,
                'arrItem' => ['a', 'b' => 'c']
            ]
            , $this->object->object2array(new \Test\ObjectForTesting()));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::objectToID
     */
    public function testObjectToID()
    {
        $this->object->setDataValue('kod', 'TEST');
        $this->assertEquals('code:TEST',
            $this->object->objectToID($this->object));

        $this->assertEquals('TEST', $this->object->objectToID('TEST'));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::performRequest
     */
    public function testPerformRequest()
    {
        $evidence = $this->object->getEvidence();
        switch ($evidence) {
            case null:
            case '':
            case 'c':
            case 'test':
            case 'status':
            case 'nastaveni':
                $this->object->performRequest(null, 'GET', 'xml');
                break;

            default:
                $json = $this->object->performRequest(null, 'GET', 'json');
                $xml  = $this->object->performRequest(null, 'GET', 'xml');
                break;
        }

        //404 Test
        $this->assertNull($this->object->performRequest('error404.json'));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::setAction
     */
    public function testSetAction()
    {
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                $this->object->setAction('none');
                break;
            default:
                $this->assertTrue($this->object->setAction('new'));
                $this->object->actionsAvailable = [];
                $this->assertFalse($this->object->setAction('none'));
                $this->object->actionsAvailable = ['copy'];
                $this->assertFalse($this->object->setAction('none'));
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getEvidence
     */
    public function testGetEvidence()
    {
        $this->assertEquals($this->object->evidence,
            $this->object->getEvidence());
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getCompany
     */
    public function testGetCompany()
    {
        $this->assertEquals($this->object->company, $this->object->getCompany());
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getResponseEvidence
     */
    public function testGetResponseEvidence()
    {
        $responseEvidence = $this->object->getResponseEvidence();
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                break;
            default:
                $this->assertEquals($this->object->getEvidence(),
                    $responseEvidence);
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getLastInsertedId
     * @depends testInsertToFlexiBee
     */
    public function testGetLastInsertedId()
    {
        $this->assertNotEmpty($this->object->getLastInsertedId());
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::xml2array
     */
    public function testXml2array()
    {
        $xml = '<card xmlns="http://businesscard.org">
   <name>John Doe</name>
   <title>CEO, Widget Inc.</title>
   <email>john.doe@widget.com</email>
   <phone>(202) 456-1414</phone>
   <logo url="widget.gif"/>
   <a><b>c</b></a>
 </card>';

        $data = ['name' => 'John Doe', 'title' => 'CEO, Widget Inc.', 'email' => 'john.doe@widget.com',
            'phone' => '(202) 456-1414', 'logo' => '', 'a' => [['b' => 'c']]];


        $this->assertEquals($data, $this->object->xml2array($xml));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::disconnect
     *
     * @depends testPerformRequest
     * @depends testLoadFlexiData
     * @depends testGetFlexiRow
     * @depends testGetFlexiData
     * @depends testLoadFromFlexiBee
     * @depends testInsertToFlexiBee
     * @depends testIdExists
     * @depends testRecordExists
     * @depends testGetColumnsFromFlexibee
     * @depends testSearchString
     */
    public function testDisconnect()
    {
        $this->object->disconnect();
        $this->assertNull($this->object->curl);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::__destruct
     * @depends testDisconnect
     */
    public function testdestruct()
    {
        $this->markTestSkipped();
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getFlexiRow
     */
    public function testGetFlexiRow()
    {
        $this->object->getFlexiRow(0);
        $this->object->getFlexiRow(1);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getFlexiData
     */
    public function testGetFlexiData()
    {
        $evidence = $this->object->getEvidence();
        switch ($evidence) {
            case null:
                $this->object->getFlexiData();
                break;
            case 'c':
                $this->object->evidence  = 'c';
                $this->object->prefix    = '';
                $this->object->company   = '';
                $this->object->nameSpace = 'companies';
                $flexidata               = $this->object->getFlexiData();
                $this->assertArrayHasKey('company', $flexidata);
                break;
            case 'evidence-list':
                $flexidata               = $this->object->getFlexiData(null,
                    ['detail' => 'id']);
                $this->assertArrayHasKey('evidenceType', $flexidata[0]);
                break;

            default:
                $flexidata = $this->object->getFlexiData(null,
                    ['detail' => 'id']);

                if (is_array($flexidata)) {
                    if (count($flexidata)) {
                        $this->markTestSkipped('Empty evidence');
                    } else {
                        $this->assertArrayHasKey(0, $flexidata);

                        $this->assertArrayHasKey('id', $flexidata[0]);
                        $filtrered = $this->object->getFlexiData(null,
                            ["id = ".$flexidata[0]['id'], 'detail' => 'full']);
                        $this->assertArrayHasKey(0, $filtrered);
                        $this->assertArrayHasKey('id', $filtrered[0]);
                    }
                }
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::loadFromFlexiBee
     */
    public function testLoadFromFlexiBee()
    {
        $this->object->loadFromFlexiBee();
        $this->object->loadFromFlexiBee(222);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getJsonizedData
     */
    public function testGetJsonizedData()
    {
        $this->assertEquals('{"'.$this->object->nameSpace.'":{"@version":"1.0","'.$this->object->evidence.'":{"key":"value"}}}',
            $this->object->getJsonizedData(['key' => 'value']));

        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                $this->object->getJsonizedData(['key' => 'value']);
                break;
            default:
                $this->object->setAction('copy');
                $this->assertEquals('{"'.$this->object->nameSpace.'":{"@version":"1.0","'.$this->object->evidence.'":{"key":"value"},"'.$this->object->evidence.'@action":"copy"}}',
                    $this->object->getJsonizedData(['key' => 'value']));
                break;

                $this->object->action = 'storno';
                $this->object->filter = "stavUhrK = 'stavUhr.uhrazeno'";
                $this->object->getJsonizedData([]);
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getDataForJSON
     */
    public function testGetDataForJson()
    {
        $this->object->getDataForJSON();
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::idExists
     */
    public function testIdExists()
    {
        $this->assertFalse($this->object->idExists('nonexistent'));
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                break;
            default:
                $first = $this->object->getColumnsFromFlexibee(['id'],
                    ['limit' => 1], 'id');
                if (empty($first)) {
                    $this->markTestSkipped('empty evidence ?');
                } else {
                    $this->object->setData($first);
                    $this->assertTrue($this->object->idExists());
                }
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getRecordID
     */
    public function testGetRecordID()
    {
        $this->object->setData([$this->object->getKeyColumn() => 10]);
        $this->assertEquals(10, $this->object->getRecordID());
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::recordExists
     */
    public function testRecordExists()
    {
        $evidence = $this->object->getEvidence();

        switch ($evidence) {
            case null:
            case 'c':
            case 'status':
            case 'evidence-list':
                $this->object->recordExists(['id' => 1]);
                break;

            default:
                $flexidata = $this->object->getFlexiData(null,
                    ['limit' => 1, 'detail' => 'id']);
                if (is_array($flexidata) && !count($flexidata)) {
                    $this->assertFalse($this->object->recordExists(['id' => 1]),
                        'Record ID 1 exists in empty evidence ?');
                } else {
                    if (!is_null($flexidata)) {
                        $this->object->setData(['id' => (int) $flexidata[0]['id']]);
                        $this->assertTrue($this->object->recordExists(),
                            'First record exists test failed');
                        $this->assertFalse($this->object->recordExists(['id' => 0]),
                            'Record ID 0 exists');
                        $this->assertFalse($this->object->recordExists(['unexistent' => 1]),
                            'Unexistent Record exist ?');
                    }
                }
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getColumnsFromFlexibee
     */
    public function testGetColumnsFromFlexibee()
    {

        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                break;
            default:
                $structure = $this->object->getColumnsInfo();

                $columns = $this->object->getColumnsFromFlexibee([current(array_keys($structure))],
                    ['limit' => 1], 'id');
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getExternalID
     */
    public function testGetExternalID()
    {
        $this->assertTrue(empty($this->object->getExternalID('ext:test:10'))); //ext: does not exist

        $this->object->setDataValue('external-ids',
            ['ext:doe:22', 'ext:test:10']);

        $this->assertEquals('ext:doe:22', $this->object->getExternalID());
        $this->assertEquals('10', $this->object->getExternalID('test'));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getGlobalVersion
     */
    public function testGetGlobalVersion()
    {
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                $this->object->getGlobalVersion();
                break;
            default:
                $this->assertInternalType("int",
                    $this->object->getGlobalVersion(),
                    'error obtaining of GlobalVersion');
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getResponseFormat
     */
    public function testGetResponseFormat()
    {
        $this->object->performRequest(null, 'GET', 'json');
        $this->assertEquals('application/json',
            $this->object->getResponseFormat());
        $this->object->performRequest(null, 'GET', 'xml');
        $this->assertEquals('application/xml',
            $this->object->getResponseFormat());
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getKod
     */
    public function testGetKod()
    {
        $testString = [];
        $this->assertEquals('code:CODE',
            $this->object->getKod([$this->object->keyColumn => 'code']));

        $testString[$this->object->nameColumn] = 'Fish clamp -  Úchytka pro instalaci samonosných kabelů '
            .'(3.5 mm)';
        $code0                                 = $this->object->getKod($testString);
        $this->assertEquals('code:FISHCLAMPUCHYTKAPR', $code0);
        $code1                                 = $this->object->getKod($testString,
            false);
        $this->assertEquals('code:FISHCLAMPUCHYTKAPR', $code1);
        $code2                                 = $this->object->getKod($testString);
        $this->assertEquals('code:FISHCLAMPUCHYTKAPR1', $code2);
        $this->object->setData($testString);
        $code3                                 = $this->object->getKod();
        $this->assertEquals('code:FISHCLAMPUCHYTKAPR2', $code3);

        $this->assertEquals('code:TEST',
            $this->object->getKod([$this->object->nameColumn => 'test']));

        $this->assertEquals('code:TEST1', $this->object->getKod('test'));

        $this->assertEquals('code:TEST2',
            $this->object->getKod(['kod' => 'test']));
        $this->assertEquals('code:NOTSET', $this->object->getKod(['kod' => '']));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::logResult
     */
    public function testLogResult()
    {
        $this->object->cleanMessages();
        $success = json_decode('{"winstrom":{"@version":"1.0","success":"true",'
            .'"stats":{"created":"0","updated":"1","deleted":"0","skipped":"0"'
            .',"failed":"0"},"results":[{"id":"1","request-id":"ext:SōkoMan.item'
            .':5271","ref":"/c/spoje_net_s_r_o_1/skladovy-pohyb/1.json"}]}}');
        $this->object->logResult(current($this->object->object2array($success)),
            'http://test');

        $this->assertArrayHasKey('info', $this->object->getStatusMessages(true));

        $error                          = json_decode('{"winstrom":{"@version":"1.0","success":"false",'
            .'"stats":{"created":"0","updated":"0","deleted":"0","skipped":"0"'
            .',"failed":"0"},"results":[{"errors":[{"message":"cz.winstrom.'
            .'service.WSBusinessException: Zadaný kód není unikátní.\nZadaný'
            .' kód není unikátní."}]}]}}');
        $this->object->lastResponseCode = 500;
        $this->object->logResult(current($this->object->object2array($error)));
        $this->assertArrayHasKey('error', $this->object->getStatusMessages(true));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::flexiUrl
     */
    public function testFlexiUrl()
    {
        $this->assertEquals("a eq '1' and b eq 'foo'",
            $this->object->flexiUrl(['a' => 1, 'b' => 'foo'], 'and'));
        $this->assertEquals("a eq '1' or b eq 'bar'",
            $this->object->flexiUrl(['a' => 1, 'b' => 'bar'], 'or'));
        $this->assertEquals("a eq true or b eq false",
            $this->object->flexiUrl(['a' => true, 'b' => false], 'or'));
        $this->assertEquals("a is null and b is not null",
            $this->object->flexiUrl(['a' => null, 'b' => '!null'], 'and'));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::unifyResponseFormat
     */
    public function testunifyResponseFormat()
    {
        $this->assertNull($this->object->unifyResponseFormat(null));
        //One Row Test

        $responseEvidence = $this->object->getResponseEvidence();
        if (empty($responseEvidence)) {
            $responseEvidence       = $this->object->evidence = 'test';
        }

        $test1raw = [$responseEvidence =>
            ['id' => 1, 'name' => 'value']
        ];

        $test1expected = [$responseEvidence =>
            [
                ['id' => 1, 'name' => 'value']
            ]
        ];

        $this->assertEquals($test1expected,
            $this->object->unifyResponseFormat($test1raw));

        //Two Row Test
        $test2Raw = [$this->object->getResponseEvidence() =>
            [
                ['id' => 1, 'name' => 'value'],
                ['id' => 2, 'name' => 'value2']
            ]
        ];

        $test2expected = [$this->object->getResponseEvidence() =>
            [
                ['id' => 1, 'name' => 'value'],
                ['id' => 2, 'name' => 'value2']
            ]
        ];

        $this->assertEquals($test2expected,
            $this->object->unifyResponseFormat($test2Raw));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::__toString
     */
    public function testtoString()
    {
        $id = '123';
        $this->object->setMyKey($id);
        $this->assertEquals($id, (string) $this->object);

        $this->object->setDataValue('kod', 'test');
        $this->assertEquals('code:TEST', (string) $this->object);

        $identifer = 'ext:test:123';
        $this->object->setMyKey($identifer);
        $this->assertEquals($identifer, (string) $this->object);

        $this->object->dataReset();
        $this->assertEquals('', $this->object->__toString());
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::draw
     */
    public function testDraw($whatWant = NULL)
    {
        $this->object->setDataValue('kod', 'test');
        $this->assertEquals('code:TEST', $this->object->draw());
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getColumnsInfo
     */
    public function testgetColumnsInfo()
    {
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'evidence-list':
                $this->assertNull($this->object->getColumnsInfo());
                break;
            default:
                $this->assertNotEmpty($this->object->getColumnsInfo(),
                    'Cannot obtain structure for '.$this->object->getEvidence());
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getActionsInfo
     */
    public function testgetActionsInfo()
    {
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                $this->assertNull($this->object->getActionsInfo());
                $this->assertNotEmpty($this->object->getActionsInfo('faktura-vydana'),
                    'Cannot obtain actions for na evidence');
                break;
            default:
                $this->assertNotEmpty($this->object->getActionsInfo(),
                    'Cannot obtain actions for '.$this->object->getEvidence());
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getRelationsInfo
     */
    public function testgetRelationsInfo()
    {
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'strom-cenik':
            case 'ucetni-obdobi':
            case 'evidence-list':
                $this->assertNull($this->object->getRelationsInfo());
                break;
            default:
                $this->assertNotEmpty($this->object->getRelationsInfo(),
                    'Cannot obtain relations for '.$this->object->getEvidence());
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getEvidenceUrl
     */
    public function testgetEvidenceUrl()
    {
        $this->assertNotEmpty($this->object->getEvidenceUrl());
        $this->assertNotEmpty($this->object->getEvidenceUrl('/properties'));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::evidenceToClassName
     */
    public function testevidenceToClassName()
    {
        $this->assertEquals('FakturaVydana',
            $this->object->evidenceToClassName('faktura-vydana'));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getEvidenceInfo
     */
    public function testGetEvidenceInfo()
    {
        $eInfo = $this->object->getEvidenceInfo();
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                break;
            default:
                $this->assertArrayHasKey('evidencePath', $eInfo);
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getEvidenceName
     */
    public function testGetEvidenceName()
    {
        $evidenceName = $this->object->getEvidenceName();
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                break;
            default:
                $this->assertNotEmpty($evidenceName);
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::saveResponseToFile
     */
    public function testSaveResponseToFile()
    {
        $tmp = sys_get_temp_dir().'/'.tmpfile();
        $this->object->saveResponseToFile($tmp);
        $this->assertFileExists($tmp);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getFirstRecordID()
     */
    public function testgetFirstRecordID()
    {
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                break;
            default:
                $firstID = $this->object->getFirstRecordID();
                break;
        }
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::getVazby
     * @expectedException \Exception
     */
    public function testGetVazby()
    {
        switch ($this->object->getEvidence()) {
            case '':
            case 'c':
            case 'hooks':
            case 'status':
            case 'changes':
            case 'nastaveni':
            case 'evidence-list':
                break;
            default:
                $this->object->getVazby($this->object->getMyKey());
                break;
        }
        $this->object->getVazby();
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::evidenceUrlWithSuffix
     */
    public function testEvidenceUrlWithSuffix()
    {
        $urlraw = $this->object->getEvidenceURL();
        $lala   = $this->object->evidenceUrlWithSuffix('lala');
        $this->assertEquals($urlraw.'/lala', $lala);
        $lolo   = $this->object->evidenceUrlWithSuffix('?lele');
        $this->assertEquals($urlraw.'?lele', $lolo);
        $lulu   = $this->object->evidenceUrlWithSuffix(';lulu');
        $this->assertEquals($urlraw.';lulu', $lulu);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::join
     * @expectedException \Ease\Exception
     */
    public function testJoin()
    {
        $ada = new FlexiBeeRO(['id' => 'A'], ['evidence' => 'adresar']);
        $adb = new FlexiBeeRO(['id' => 'B'], ['evidence' => 'adresar']);
        $this->assertTrue($this->object->join($ada));
        $this->assertTrue($this->object->join($adb));
        $ads = new \stdClass();
        $this->object->join($ads);
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::addUrlParams
     */
    public function testAddUrlParams()
    {
        $this->assertEquals('http://vitexsoftware.cz/path?id=1&a=b',
            $this->object->addUrlParams('http://vitexsoftware.cz/path?a=b',
                ['id' => 1], TRUE));
    }

    /**
     * @covers FlexiPeeHP\FlexiBeeRO::addDefaultUrlParams
     */
    public function testAddDefaultUrlParams()
    {
        $this->object->defaultUrlParams       = [];
        $this->assertEquals('http://vitexsoftware.cz?a=b',
            $this->object->addDefaultUrlParams('http://vitexsoftware.cz?a=b'));
        $this->object->defaultUrlParams['id'] = 1;
        $this->assertEquals('http://vitexsoftware.cz/path?a=b&id=1',
            $this->object->addDefaultUrlParams('http://vitexsoftware.cz/path?a=b'));
    }

    public function testFlexiDateToDateTime()
    {
        $this->assertEquals(1495749600,
            FlexiBeeRO::flexiDateToDateTime('2017-05-26+02:00')->getTimestamp());
    }

    public function testFlexiDateTimeToDateTime()
    {
        $this->assertEquals(1506412853,
            FlexiBeeRO::flexiDateTimeToDateTime('2017-09-26T10:00:53.755+02:00')->getTimestamp());
    }

    public function testSetFilter()
    {
        $this->object->setFilter('X');
        $this->object->setFilter(['a' => 'b']);
    }
}
