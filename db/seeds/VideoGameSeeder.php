<?php

use Phinx\Seed\AbstractSeed;

class VideoGameSeeder extends AbstractSeed
{
    public function run()
    {
      $data = [
        [
          'id' => 1,
          'title' => 'Super Mario Bros. / Duck Hunt',
          'image' => '2362278-nes_supermariobrosduckhunt_2.jpg',
          'system' => 'Nintendo Entertainment System',
          'genre' => 'Adventure,Light-Gun Shooter,Platformer',
          'developer' => 'Nintendo',
          'description' => 'A compilation of Duck Hunt and Super Mario Bros. Duck Hunt featured a lightgun shooting game while Super Mario Bros. is a well-known platformer.',
          'released_on' => '1988-11-01',
          'notes' => 'Sample game in your inventory!',
          'completed' => 1
        ],
        [
          'id' => 2,
          'title' => 'Metroid Prime',
          'image' => '2550128-primeclean.jpg',
          'system' => 'GameCube',
          'genre' => 'First-Person Shooter,Platformer,Action-Adventure',
          'developer' => 'Retro Studios,Nintendo R&D1,Nintendo EAD,Nintendo SPD Group No.3',
          'description' => 'A compilation of Duck Hunt and Super Mario Bros. Duck Hunt featured a lightgun shooting game while Super Mario Bros. is a well-known platformer.',
          'released_on' => '2002-11-18',
          'notes' => 'Sample game in your inventory!',
          'completed' => 1
        ],
        [
          'id' => 3,
          'title' => 'Baldur\'s Gate II: Shadows of Amn',
          'image' => '643367-baldur_s_gate_ii___shadow_of_amn_boxart.jpg',
          'system' => 'Mac',
          'genre' => 'Role-Playing',
          'developer' => 'BioWare',
          'description' => 'Take hold of your destiny as you journey across mysterious lands, encounter many magical creatures and meet many memorable characters in a large fantasy world.',
          'released_on' => '2000-09-24',
          'notes' => 'Sample game in your inventory!'
        ]
      ];

      $game = $this->table('game');
      $game->truncate();
      $game
        ->insert($data)
        ->save();

      $nextID = count($data) +1;
      $this->execute("ALTER TABLE game AUTO_INCREMENT = $nextID");
    }
}
