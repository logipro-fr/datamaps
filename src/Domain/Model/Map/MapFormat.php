<?php

namespace Datamaps\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Exceptions\WrongFormatException;
use Datamaps\Infrastructure\Shared\CurrentWorkDirPath;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Errors\ValidationError;
use Opis\JsonSchema\ValidationResult;
use Opis\JsonSchema\Validator;

use function Safe\json_decode;

class MapFormat
{
    public readonly string $mapId;
    /** @var array<array<float>> $bounds */
    public readonly array $bounds;
    /** @var array<\stdClass> $layers */
    public readonly array $layers;

    public function __construct(\stdClass $data)
    {
        $this->validateData($data);

        if (property_exists($data, "mapId")) {
            $mapId = new MapId($data->mapId);
        } else {
            $mapId = new MapId();
        }
        if (property_exists($data, "layers")) {
            $layers = $data->layers;
        } else {
            $layers = [];
        }
        $this->mapId = $mapId;
        $this->bounds = $data->bounds;
        $this->layers = $layers;
    }

    public static function fromJSON(string $jsonPayload): MapFormat
    {
        /** @var \stdClass $data */
        $data = json_decode($jsonPayload);
        return new self($data);
    }

    private function validateData(\stdClass $data): void
    {

        $validator = new Validator();
        $resolver = $validator->resolver();
        if ($resolver != null) {
            $resolver->registerFile(
                'http://api.example.com/profile.json',
                CurrentWorkDirPath::getPath() . '/src/Domain/Model/Map/MapSchema.json'
            );

            $result = $validator->validate($data, 'http://api.example.com/profile.json');

            if (!$result->isValid()) {
                throw new WrongFormatException($this->getErrorMessage($result), WrongFormatException::ERROR_CODE);
            }
        }
    }

    private function getErrorMessage(ValidationResult $result): string
    {
        /** @var ValidationError $error */
        $error = $result->error();
        $format = ((new ErrorFormatter())->format($error));
        return array_key_first($format) . ': ' . array_values($format)[0][0];
    }
}
