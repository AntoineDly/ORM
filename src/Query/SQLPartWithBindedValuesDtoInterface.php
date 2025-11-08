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

use AntoineDly\ORM\Dtos\DtoInterface;

interface SQLPartWithBindedValuesDtoInterface extends SQLPartDtoInterface
{
    public function getBindValues(): BindValueDtoCollection;
}
