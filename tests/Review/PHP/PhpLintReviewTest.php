<?php
/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace StaticReview\Tests\Review\PHP;

use \Mockery;
use \PHPUnit_Framework_TestCase as TestCase;

class PhpLintReviewTest extends TestCase
{
    protected $file;

    protected $review;

    public function setUp()
    {
        $this->file   = Mockery::mock('StaticReview\File\FileInterface');
        $this->review = Mockery::mock('StaticReview\Review\PHP\PhpLintReview[getProcess]');
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testCanReview()
    {
        $this->file->shouldReceive('getFileLocation')->once()->andReturn(__FILE__);

        $this->assertTrue($this->review->canReview($this->file));
    }

    public function testReview()
    {
        $this->file->shouldReceive('getFileLocation')->twice()->andReturn(__FILE__);

        $process = Mockery::mock('Symfony\Component\Process\Process')->makePartial();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->once()->andReturn(false);
        $process->shouldReceive('getOutput')->once()->andReturn('Parse error: syntax error, test in ' . __FILE__ . PHP_EOL . 'test' . PHP_EOL);

        $this->review->shouldReceive('getProcess')->once()->andReturn($process);

        $reporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');
        $reporter->shouldReceive('error')->once();

        $this->review->review($reporter, $this->file);
    }
}
