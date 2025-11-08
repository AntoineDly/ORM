<?php

declare(strict_types=1);

/*
 * This file is part of the AntoineDly/ORM package.
 *
 * (c) Antoine Delaunay <antoine.delaunay333@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AntoineDly\ORM\Query;

final readonly class PreparedSQLDto
{
    public function __construct(
        public string $sql,
        public BindValueDtoCollection $bindValues
    ) {
    }
}
