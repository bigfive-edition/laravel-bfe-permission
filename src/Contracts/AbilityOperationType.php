<?php

namespace BigFiveEdition\Permission\Contracts;

abstract class AbilityOperationType
{
	const READ_ALL = 'read_all';
	const READ = 'read';
	const CREATE = 'create';
	const UPDATE = 'update';
	const DELETE = 'delete';

	const ALL = ['read_all', 'read', 'create', 'update', 'delete'];
}
