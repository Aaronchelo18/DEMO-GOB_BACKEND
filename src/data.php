<?php
declare(strict_types=1);

function siscat_establishment(): array
{
    return [
        'id' => 'cs-juan-guerra',
        'name' => 'CENTRO DE SALUD JUAN GUERRA (I-3)',
        'network' => 'SAN MARTIN',
        'micro_network' => 'JUAN GUERRA',
        'category' => 'PRIMER NIVEL DE ATENCION - I-3 (CPA)',
    ];
}

function siscat_user(): array
{
    return [
        'id' => 'user-demo',
        'name' => 'JAUREGUI SAAVEDRA HERMAN',
        'expires' => '2026-12-31',
    ];
}

function siscat_modules(): array
{
    return [
        ['key' => 'infraestructura', 'label' => 'INFRAESTRUCTURA', 'href' => 'consulta-externa.php'],
        ['key' => 'equipamiento', 'label' => 'EQUIPAMIENTO', 'href' => '#'],
        ['key' => 'recursos_humanos', 'label' => 'RECURSOS HUMANOS', 'href' => '#'],
        ['key' => 'organizacion', 'label' => 'ORGANIZACION DE LA ATENCION', 'href' => '#'],
    ];
}

function siscat_upss(): array
{
    return [
        ['key' => 'consulta_externa', 'label' => 'CONSULTA EXTERNA'],
        ['key' => 'hospitalizacion', 'label' => 'HOSPITALIZACION'],
        ['key' => 'emergencia', 'label' => 'EMERGENCIA'],
        ['key' => 'centro_obstetrico', 'label' => 'CENTRO OBSTETRICO'],
        ['key' => 'patologia_clinica', 'label' => 'PATOLOGIA CLINICA'],
        ['key' => 'diagnostico', 'label' => 'DIAGNOSTICO POR IMAGENES'],
        ['key' => 'farmacia', 'label' => 'FARMACIA'],
        ['key' => 'esterilizacion', 'label' => 'CENTRAL DE ESTERILIZACION'],
        ['key' => 'rehabilitacion', 'label' => 'MEDICINA DE REHABILITACION'],
        ['key' => 'nutricion', 'label' => 'NUTRICION Y DIETETICA'],
    ];
}

function siscat_services(): array
{
    return [
        'medicina' => [
            'label' => 'Medicina',
            'description' => 'Consultorio medico general',
            'groups' => [
                'infraestructura' => [
                    'label' => 'Infraestructura',
                    'items' => [
                        item('consultorio-medico-lavamanos', 'Consultorio fisico con lavamanos', 'Ambiente de atencion medica', 1),
                        item('consultorio-medico-privacidad', 'Privacidad para examen clinico', 'Separacion visual del area de examen', 1),
                    ],
                ],
                'equipamiento' => [
                    'label' => 'Equipamiento',
                    'items' => [
                        item('tensiometro-adulto', 'Tensiometro adulto', 'Control de presion arterial', 1),
                        item('estetoscopio-adulto', 'Estetoscopio adulto', 'Evaluacion clinica', 1),
                        item('termometro-clinico', 'Termometro clinico', 'Control de temperatura', 1),
                    ],
                ],
                'rrhh' => [
                    'label' => 'Recursos humanos',
                    'items' => [
                        item('medico-general-consulta', 'Medico general', 'Atencion medica en consulta externa', 1),
                        item('tecnico-consulta-externa', 'Tecnico de enfermeria', 'Apoyo al servicio de consulta externa', 1, 'Opcional'),
                    ],
                ],
            ],
        ],
        'enfermeria' => [
            'label' => 'Enfermeria',
            'description' => 'Topico para procedimientos',
            'groups' => [
                'infraestructura' => [
                    'label' => 'Infraestructura',
                    'items' => [
                        item('topico-lavamanos', 'Topico para procedimientos', 'Procedimientos menores', 1),
                    ],
                ],
                'equipamiento' => [
                    'label' => 'Equipamiento',
                    'items' => [
                        item('coche-curaciones', 'Coche de curaciones', 'Curaciones y procedimientos', 1),
                        item('balanza-tallimetro', 'Balanza con tallimetro', 'Control antropometrico', 1),
                        item('set-curacion', 'Set de curacion', 'Material para procedimiento', 3, 'Opcional'),
                    ],
                ],
                'rrhh' => [
                    'label' => 'Recursos humanos',
                    'items' => [
                        item('tecnico-enfermeria', 'Personal tecnico de enfermeria', 'Turno de atencion', 1),
                    ],
                ],
            ],
        ],
        'obstetricia' => [
            'label' => 'Obstetricia',
            'description' => 'Consultorio de obstetricia',
            'groups' => [
                'infraestructura' => [
                    'label' => 'Infraestructura',
                    'items' => [
                        item('consultorio-obstetricia-lavamanos', 'Consultorio de obstetricia con lavamanos', 'Atencion materna', 1),
                    ],
                ],
                'equipamiento' => [
                    'label' => 'Equipamiento',
                    'items' => [
                        item('camilla-ginecologica', 'Camilla ginecologica', 'Evaluacion obstetrica', 1),
                        item('doppler-fetal', 'Doppler fetal', 'Control fetal', 1),
                        item('especulo-vaginal', 'Especulo vaginal', 'Examen ginecologico', 5, 'Opcional'),
                    ],
                ],
                'rrhh' => [
                    'label' => 'Recursos humanos',
                    'items' => [
                        item('obstetra', 'Obstetra', 'Atencion profesional', 1),
                    ],
                ],
            ],
        ],
    ];
}

function item(string $id, string $label, string $detail, int $defaultQty, string $required = 'Obligatorio'): array
{
    return [
        'id' => $id,
        'label' => $label,
        'required' => $required,
        'detail' => $detail,
        'default_qty' => $defaultQty,
    ];
}

function service_group_items(string $serviceKey, string $groupKey): ?array
{
    $services = siscat_services();
    return $services[$serviceKey]['groups'][$groupKey]['items'] ?? null;
}

function find_item(string $itemId): ?array
{
    foreach (siscat_services() as $serviceKey => $service) {
        foreach ($service['groups'] as $groupKey => $group) {
            foreach ($group['items'] as $item) {
                if ($item['id'] === $itemId) {
                    return $item + [
                        'service_key' => $serviceKey,
                        'service' => $service['label'],
                        'group_key' => $groupKey,
                        'group' => $group['label'],
                    ];
                }
            }
        }
    }

    return null;
}

function create_capture(array $payload, array $files): array
{
    $id = 'cap_' . bin2hex(random_bytes(8));
    $storedFiles = [];
    $uploadDir = storage_path('uploads');

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    foreach ($files as $file) {
        if ((int) $file['error'] !== UPLOAD_ERR_OK) {
            continue;
        }

        $name = safe_filename((string) $file['name']);
        $storedName = $id . '-' . $name;
        $target = $uploadDir . DIRECTORY_SEPARATOR . $storedName;

        if (is_uploaded_file((string) $file['tmp_name'])) {
            move_uploaded_file((string) $file['tmp_name'], $target);
        }

        $storedFiles[] = [
            'name' => $name,
            'stored_name' => $storedName,
            'type' => $file['type'],
            'size' => $file['size'],
        ];
    }

    $capture = [
        'id' => $id,
        'created_at' => date(DATE_ATOM),
        'establishment_id' => siscat_establishment()['id'],
        'data' => $payload,
        'files' => $storedFiles,
    ];

    file_put_contents(
        storage_path('captures.jsonl'),
        json_encode($capture, JSON_UNESCAPED_SLASHES) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );

    return $capture;
}

function read_captures(): array
{
    $file = storage_path('captures.jsonl');
    if (!is_file($file)) {
        return [];
    }

    $captures = [];
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $decoded = json_decode($line, true);
        if (is_array($decoded)) {
            $captures[] = $decoded;
        }
    }

    return array_reverse($captures);
}
