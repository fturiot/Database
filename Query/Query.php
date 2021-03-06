<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2013, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace {

from('Hoa')

/**
 * \Hoa\Database\Query\Select
 */
-> import('Database.Query.Select')

/**
 * \Hoa\Database\Query\Insert
 */
-> import('Database.Query.Insert')

/**
 * \Hoa\Database\Query\Update
 */
-> import('Database.Query.Update')

/**
 * \Hoa\Database\Query\Delete
 */
-> import('Database.Query.Delete')

/**
 * \Hoa\Database\Query\Where
 */
-> import('Database.Query.Where');

}

namespace Hoa\Database\Query {

/**
 * Class \Hoa\Database\Query.
 *
 * Multiton of queries.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2013 Ivan Enderlin.
 * @license    New BSD License
 */

class Query {

    /**
     * Multiton of queries.
     *
     * @var \Hoa\Database\Query array
     */
    protected static $_queries = array();

    /**
     * Current instance ID.
     *
     * @var \Hoa\Database\Query string
     */
    protected $_id             = null;



    /**
     * Set current instance ID.
     *
     * @access  public
     * @param   string  $id    ID.
     * @return  \Hoa\Database\Query
     */
    public function setId ( $id ) {

        $this->_id = $id;

        return $this;
    }

    /**
     * Get current instance ID.
     *
     * @access  public
     * @return  string
     */
    public function getId ( ) {

        return $this->_id;
    }

    /**
     * Start a START query.
     *
     * @access  public
     * @param   string  $column    Column.
     * @param   ...     ...
     * @return  \Hoa\Database\Query\Select
     */
    public function select ( $column = null ) {

        return $this->store(new Select(func_get_args()));
    }

    /**
     * Start an INSERT query.
     *
     * @access  public
     * @return  \Hoa\Database\Query\Insert
     */
    public function insert ( ) {

        return $this->store(new Insert());
    }

    /**
     * Start an UPDATE query.
     *
     * @access  public
     * @return  \Hoa\Database\Query\Update
     */
    public function update ( ) {

        return $this->store(new Update());
    }

    /**
     * Start a DELETE query.
     *
     * @access  public
     * @return  \Hoa\Database\Query\Delete
     */
    public function delete ( ) {

        return $this->store(new Delete());
    }

    /**
     * Start a WHERE clause.
     *
     * @access  public
     * @param   string  $expression    Expression.
     * @return  \Hoa\Database\Query\Where
     */
    public function where ( $expression ) {

        $where = new Where();

        return $this->store($where->where($expression));
    }


    /**
     * Store the current instance if necessary.
     *
     * @access  protected
     * @param   \Hoa\Database\Query\Dml  $object    Object.
     * @return  \Hoa\Database\Query\Dml
     */
    protected function store ( $object ) {

        if(null === $id = $this->getId())
            $out = $object;
        else
            $out = static::$_queries[$id] = $object;

        $this->_id = null;

        return $out;
    }

    /**
     * Get a query (a clone of it).
     *
     * @access  public
     * @param   string  $id    ID.
     * @return  \Hoa\Database\Query\Dml
     */
    public static function get ( $id ) {

        if(null === $out = static::getReference($id))
            return null;

        return clone $out;
    }

    /**
     * Get a query (not a clone of it).
     *
     * @access  public
     * @param   string  $id    ID.
     * @return  \Hoa\Database\Query\Dml
     */
    public static function getReference ( $id ) {

        if(false === array_key_exists($id, static::$_queries))
            return null;

        return static::$_queries[$id];
    }
}

}

namespace {

/**
 * Flex entity.
 */
Hoa\Core\Consistency::flexEntity('Hoa\Database\Query\Query');

}
