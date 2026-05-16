<?php

namespace App\Repository;

use App\Entity\Video;
use App\Entity\VideoPicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VideoPicture>
 */
class VideoPictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoPicture::class);
    }

    /**
     * @return VideoPicture[]
     */
    public function findByVideoOrdered(Video $video): array
    {
        return $this->createQueryBuilder('vp')
            ->where('vp.video = :video')
            ->setParameter('video', $video)
            ->orderBy('vp.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countByVideo(Video $video): int
    {
        return (int) $this->createQueryBuilder('vp')
            ->select('COUNT(vp.id)')
            ->where('vp.video = :video')
            ->setParameter('video', $video)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getNextPositionByVideo(Video $video): int
    {
        $lastPosition = $this->createQueryBuilder('vp')
            ->select('MAX(vp.position)')
            ->where('vp.video = :video')
            ->setParameter('video', $video)
            ->getQuery()
            ->getSingleScalarResult();

        return ($lastPosition ?? -1) + 1;
    }
}
