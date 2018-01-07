<?php
/**
     * Copyright (c) 2010-2018 Arne Blankerts <arne@blankerts.de>
     * All rights reserved.
     *
     * Redistribution and use in source and binary forms, with or without modification,
     * are permitted provided that the following conditions are met:
     *
     *   * Redistributions of source code must retain the above copyright notice,
     *     this list of conditions and the following disclaimer.
     *
     *   * Redistributions in binary form must reproduce the above copyright notice,
     *     this list of conditions and the following disclaimer in the documentation
     *     and/or other materials provided with the distribution.
     *
     *   * Neither the name of Arne Blankerts nor the names of contributors
     *     may be used to endorse or promote products derived from this software
     *     without specific prior written permission.
     *
     * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
     * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT  * NOT LIMITED TO,
     * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
     * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER ORCONTRIBUTORS
     * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
     * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
     * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
     * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
     * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
     * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
     * POSSIBILITY OF SUCH DAMAGE.
     *
     * @package    phpDox
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     * @license    BSD License
     *
     */
namespace TheSeer\phpDox {

    class Version {

        /**
         * @var string
         */
        private $release;

        /**
         * @var string
         */
        private $version = NULL;

        public function __construct($release) {
            $this->release = $release;
        }

        public function __toString() {
            return $this->getInfoString();
        }

        /**
         * @return string
         */
        public function getVersion() {
            if ($this->version == NULL) {
                $this->version = $this->initialize();
            }
            return $this->version;
        }

        public function getInfoString() {
            return 'phpDox ' . $this->getVersion() . " - Copyright (C) 2010 - " . date('Y', getenv('SOURCE_DATE_EPOCH') ?: time()) . " by Arne Blankerts and Contributors";
        }

        public function getGeneratedByString() {
            return 'Generated using ' . $this->getInfoString();
        }

        private function initialize() {
            if (!is_dir(__DIR__ . '/../../.git') || strpos(ini_get('disable_functions'), 'exec') !== false) {
                return $this->release;
            }
            $dir = getcwd();
            chdir(__DIR__);

            $devNull = strtolower(substr(PHP_OS, 0, 3)) == 'win' ? 'nul' : '/dev/null';
            $git = exec('command -p git describe --always --dirty 2>'.$devNull, $foo, $rc);
            chdir($dir);
            if ($rc === 0) {
                return $git;
            }
            return $this->release;
        }
    }
}
