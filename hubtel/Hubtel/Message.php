<?php


class Message {

    private $object;

    /**
     * Primary constructor.
     */
    public function __construct($json = null) {
        $this->object = is_object($json) ? $json : new stdClass;
    }

    /**
     * Gets apiMessageType.
     */
    public function getType() {
        return @$this->object->Type;
    }

    /**
     * Gets clientReference.
     */
    public function getClientReference() {
        return @$this->object->ClientReference;
    }

    /**
     * Gets content.
     */
    public function getContent() {
        return @$this->object->Content;
    }

    /**
     * Gets from.
     */
    public function getFrom() {
        return @$this->object->From;
    }

    /**
     * Gets registeredDelivery.
     */
    public function getRegisteredDelivery() {
        return @$this->object->RegisteredDelivery;
    }

    /**
     * Gets to.
     */
    public function getTo() {
        return @$this->object->To;
    }


    /**
     * Sets Type.
     */
    public function setType($value) {
        if (is_int($value)) {
            if ($value != MessageType::BINARY_MESSAGE && $value != MessageType::TEXT_MESSAGE && $value != MessageType::UNICODE_MESSAGE) {
                $this->object->Type = "Unset";
            } else {
                $this->object->Type = $value;
            }
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'int'");
    }

    /**
     * Sets clientReference.
     */
    public function setClientReference($value) {
        if ($value === null || is_string($value)) {
            $this->object->ClientReference = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets content.
     */
    public function setContent($value) {
        if ($value === null || is_string($value)) {
            $this->object->Content = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets from.
     */
    public function setFrom($value) {
        if ($value === null || is_string($value)) {
            $this->object->From = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets registeredDelivery.
     */
    public function setRegisteredDelivery($value) {
        if (is_bool($value)) {
            $this->object->RegisteredDelivery = $value ? "true" : "false";
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets to.
     */
    public function setTo($value) {
        if ($value === null || is_string($value)) {
            $this->object->To = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }
}
