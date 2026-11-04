<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;

// require的两种写法:

/**
 * ====================
 * 方法1:
 * 1. 文件一被加载就执行 require
 * 2. 如果路径写错，整个文件都解析失败
 * 3. 在某些自动加载 / 并行测试场景下可能出问题
 * 4. 不符合 PHPUnit 常见惯例
 *
 *
 * =====================
 * 方法2:
 * 写在 setUpBeforeClass() 由 PHPUnit 控制加载时机 只在真正跑这个测试类时才加载 更清晰、更规范
 */

// require_once dirname(__DIR__) . 'stages/01-php-syntax/exercises/my-status.php';


final class MyStatusTest extends TestCase {


  public static function setUpBeforeClass(): void {
    require_once __DIR__ . '/../stages/01-php-syntax/exercises/my-status.php';
  }





  // 入参是关联数组, 只接受 status 字段为 enabled/disabled。缺少、空串、数组、其他值抛 InvalidArgumentException
  public function testValidStatus(): void
  {
    self::assertSame('ok', readStatus(['status' => 'enabled']));
    self::assertSame('ok', readStatus(['status' => 'disabled']));
  }

  // 失败用例: 缺少、空串、数组、其他值都要抛 InvalidArgumentException

  public function testInvalidInputAreRejected(): void {
    $invalidInputs = [
      'missing key'          => [],
      'null'                 => ['status' => null],
      'empty string'         => ['status' => ''],
      'array'                => ['status' => []],
      'integer'              => ['status' => 1],
      'float'                => ['status' => 1.5],
      'true'                 => ['status' => true],
      'false'                => ['status' => false],
      'other string'         => ['status' => 'pending'],
      'uppercase'            => ['status' => 'Enabled'],
      'with spaces'          => ['status' => ' enabled '],
      'numeric string'       => ['status' => '0'],
    ];



    forEach($invalidInputs as $case => $input) {
      try {
        readStatus($input);
        self::fail("{$case} was accepted");
      } catch(InvalidArgumentException $e) {
        self::assertInstanceOf(InvalidArgumentException::class, $e);
      }
    }
  }

}