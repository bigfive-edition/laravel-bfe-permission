<?php

namespace BigFiveEdition\Permission\Contracts;

abstract class AbilityOperationType
{
	const READ_ALL = 'read_all';
	const READ_OWNED = 'read_owned';
	const READ = 'read';
	const CREATE = 'create';
	const UPDATE = 'update';
	const UPDATE_OWNED = 'update_owned';
	const DELETE = 'delete';
	const DELETE_OWNED = 'delete_owned';

	const ALL = ['read_all', 'read', 'create', 'update', 'delete'];
}
