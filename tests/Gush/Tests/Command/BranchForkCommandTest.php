<?php

/**
 * This file is part of Gush.
 *
 * (c) Luis Cordova <cordoval@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Gush\Tests\Command;

use Gush\Command\BranchForkCommand;
use Gush\Tests\Fixtures\OutputFixtures;

/**
 * @author Luis Cordova <cordoval@gmail.com>
 */
class BranchForkCommandTest extends BaseTestCase
{
    const TEST_USERNAME = 'cordoval';

    public function testCommand()
    {
        $processHelper = $this->expectProcessHelper();
        $gitHelper = $this->expectGitHelper();
        $tester = $this->getCommandTester($command = new BranchForkCommand());
        $command->getHelperSet()->set($processHelper, 'process');
        $command->getHelperSet()->set($gitHelper, 'git');

        $tester->execute(['--org' => 'gushphp', '--repo' => 'gush']);

        $this->assertEquals(OutputFixtures::BRANCH_FORK, trim($tester->getDisplay()));
    }

    private function expectProcessHelper()
    {
        $processHelper = $this->getMock(
            'Gush\Helper\ProcessHelper',
            ['runCommands']
        );
        $processHelper->expects($this->once())
            ->method('runCommands')
            ->with([
                [
                    'line' => 'git remote add cordoval git@github.com:cordoval/gush.git',
                    'allow_failures' => true
                ],
            ])
        ;

        return $processHelper;
    }

    private function expectGitHelper()
    {
        $gitHelper = $this->getMock(
            'Gush\Helper\GitHelper',
            ['getBranchLocalUser']
        );
        $gitHelper->expects($this->once())
            ->method('getBranchName')
            ->will($this->returnValue(self::TEST_USERNAME))
        ;

        return $gitHelper;
    }
}