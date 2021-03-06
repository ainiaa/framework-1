<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information,
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Model;

use Doctrine\ORM\EntityManager;

/**
 * Basic model facade
 *
 * @author	Patrik Votoček
 */
class Facade extends \Nette\Object implements IDao, IObjectFactory, IQueryable
{
	/** @var \Doctrine\ORM\EntityManager */
	protected $em;
	/** @var Repository */
	protected $repository;

	/**
	 * @param \Doctrine\ORM\EntityManager
	 * @param Repository
	 */
	public function __construct(EntityManager $em, Repository $repository)
	{
		$this->em = $em;
		$this->repository = $repository;
	}

	/**
	 * @param array|NULL
	 * @return object
	 */
	public function create(array $values = array())
	{
		$ref = $this->em->getClassMetadata($this->repository->getClassName())->getReflectionClass();
		if (empty($values)) {
			return $ref->newInstance();
		}

		return $ref->newInstanceArgs($values);
	}

	/**
	 * @param object
	 * @param bool
	 */
	public function save($entity, $withoutFlush = self::FLUSH)
	{
		try {
			$this->em->persist($entity);
			if ($withoutFlush == self::FLUSH) {
				$this->em->flush();
			}

			return $this;
		} catch(\Doctrine\DBAL\DBALException $e) {
			Helper::convertException($e);
		} catch (\PDOException $e) {
			Helper::convertException($e);
		}
	}

	/**
	 * @param object
	 * @param bool
	 */
	public function remove($entity, $withoutFlush = self::FLUSH)
	{
		try {
			$this->em->remove($entity);
			if ($withoutFlush == self::FLUSH) {
				$this->em->flush();
			}

			return $this;
		} catch(\Doctrine\DBAL\DBALException $e) {
			Helper::convertException($e);
		} catch (\PDOException $e) {
			Helper::convertException($e);
		}
	}

	/**
	 * @param mixed
	 * @return object
	 */
	public function find($id)
	{
		return $this->repository->find($id);
	}

	/**
	 * @return array
	 */
	public function findAll()
	{
		return $this->repository->findAll();
	}

	/**
	 * @param array
	 * @return object|NULL
	 */
	public function findOneBy(array $criteria)
	{
		return $this->repository->findOneBy($criteria);
	}

	/**
	 * @param array
	 * @param array|NULL
	 * @param int|NULL
	 * @param int|NULL
	 * @return array
	 */
	public function findBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL)
	{
		return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
	}

	/**
	 * @param string|NULL
	 * @return \Doctrine\ORM\QueryBuilder|\Doctrine\CouchDB\View\AbstractQuery
	 */
	public function createQueryBuilder($alias)
	{
		return $this->repository->createQueryBuilder($alias);
	}
}

