<?php

namespace BigFiveEdition\Permission\Contracts;

abstract class AbilityOperationType
{
	const READ_ALL = 'read_all';
	const READ_ALL_OWNED = 'read_all_owned';
	const READ = 'read';
	const READ_OWNED = 'read_owned';
	const CREATE = 'create';
	const UPDATE = 'update';
	const UPDATE_OWNED = 'update_owned';
	const DELETE = 'delete';
	const DELETE_OWNED = 'delete_owned';

	const ALL = ['read_all', 'read_all_owned', 'read', 'read_owned', 'create', 'update', 'update_owned', 'delete', 'delete_owned'];
}
