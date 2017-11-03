<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Normalizer;

use App\Domain\Model\Session;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use App\Infrastructure\Normalizer\Session\FileNormalizer;
use App\Infrastructure\Normalizer\Session\QuestionNormalizer;
use App\Infrastructure\Normalizer\SessionNormalizer;
use PHPUnit\Framework\TestCase;
use Tests\Factory\CourseFactory;

class SessionNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        // Context
        $createdAt = new \DateTime();
        $course = CourseFactory::create();
        $session = new Session('uuid', 5, 'session title', 'this is the content', $course, null, true, true, $createdAt);
        $file1 = $this->prophesize(Session\File::class);
        $file2 = $this->prophesize(Session\File::class);
        $session->setFiles([$file1->reveal(), $file2->reveal()]);
        $question = $this->prophesize(Session\Question::class);

        // Mock
        $fileNormalizer = $this->prophesize(FileNormalizer::class);
        $fileNormalizer->normalize($file1->reveal())->shouldBeCalled()->willReturn(['file1']);
        $fileNormalizer->normalize($file2->reveal())->shouldBeCalled()->willReturn(['file2']);

        $questionRepository = $this->prophesize(QuestionRepositoryInterface::class);
        $questionRepository->getQuestionsOfSession($session)->shouldBeCalled()->willReturn([$question]);

        $questionNormalizer = $this->prophesize(QuestionNormalizer::class);
        $questionNormalizer
            ->normalize($question->reveal())
            ->shouldBeCalled()
            ->willReturn([
                'title' => 'question title',
                'answers' => [],
            ])
        ;


        // Normalizer
        $sessionNormalier = new SessionNormalizer(
            $fileNormalizer->reveal(),
            $questionRepository->reveal(),
            $questionNormalizer->reveal()
        );
        $result = $sessionNormalier->normalize($session, false);

        $expected = [
            'uuid' => 'uuid',
            'rank' => 5,
            'title' => 'session title',
            'content' => 'this is the content',
            'validated' => false,
            'needValidation' => true,
            'createdAt' => $createdAt,
            'updatedAt' => $createdAt,
            'contentUpdatedAt' => $createdAt,
            'files' => [
                ['file1'],
                ['file2'],
            ],
            'questions' => [
                [
                    'title' => 'question title',
                    'answers' => []
                ]
            ]
        ];

        $this->assertEquals($expected, $result);
    }
}
