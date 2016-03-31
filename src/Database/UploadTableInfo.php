<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

class UploadTableInfo
{
    public function __construct()
    {
        $this->createUser();
        $this->createCategory();
        $this->createEmoji();
    }

    public function createUser()
    {
        $user = new UserController();
        $user->createUser([
            'firstname'  => 'Olotin',
            'lastname'   => 'Temitope',
            'username'   => 'laztopaz',
            'password'   => 'tope0852',
            'email'      => 'temitope.olotin@andela.com',
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
    }

    public function createEmoji()
    {
        $emojiKeyword = 'eye, face, grin, person';

        $userId = 1;

        $created_at = date('Y-m-d h:i:s');

        $emoji = Emoji::create([
            'name'       => 'GRINNING FACE',
            'char'       => '😀',
            'created_at' => $created_at,
            'category'   => 1,
            'created_by' => $userId,
        ]);

        if ($emoji->id) {
            $createdKeyword = $this->createEmojiKeywords($emoji->id, $emojiKeyword);
        }
    }

    public function createCategory()
    {
        $created_at = date('Y-m-d h:i:s');

        $category = Category::create([
            'category_name' => 'people',
            'created_at'    => $created_at,
        ]);
    }

    public function createEmojiKeywords($emoji_id, $keywords)
    {
        if ($keywords) {
            $splittedKeywords = explode(',', $keywords);

            $created_at = date('Y-m-d h:i:s');

            foreach ($splittedKeywords as $keyword) {
                $emojiKeyword = Keyword::create([
                    'emoji_id'     => $emoji_id,
                    'keyword_name' => $keyword,
                    'created_at'   => $created_at,
                ]);
            }
        }

        return $emojiKeyword->id;
    }
}
