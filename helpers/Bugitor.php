<?php
namespace app\helpers;
/*
* This file is part of
*  _                 _ _
* | |__  _   _  __ _(_) |_ ___  _ __
* | '_ \| | | |/ _` | | __/ _ \| '__|
* | |_) | |_| | (_| | | || (_) | |
* |_.__/ \__,_|\__, |_|\__\___/|_|
*              |___/
*                 issue tracker
*
*	Copyright (c) 2009 - 2016 Jacob Moen
*	Licensed under the MIT license
*/
/**
 *
 */
class Bugitor {

    /**
     * [powered description]
     * @param  [type] $image [description]
     * @param  string $color [description]
     * @return [type]        [description]
     */
    public static function powered($image = true, $white = false, $small = false)
    {
        if($image === true)
        {
            if($small) {
                return '<a href="https://Bugitor.jacmoe.dk/" rel="external"><img width="24" height="24" title="Powered by Bugitor" alt="Powered by Bugitor" src="' . Bugitor::logo($white, $small) . '"></a>';
            } else {
                return '<a href="https://Bugitor.jacmoe.dk/" rel="external"><img width="36" height="36" title="Powered by Bugitor" alt="Powered by Bugitor" src="' . Bugitor::logo($white) . '"></a>';
            }
        }

        return 'Powered by <a href="https://Bugitor.jacmoe.dk/" rel="external">Bugitor</a>';
    }

    /**
    * Returns Bugitor logo ready to use in `<img src="`
    *
    * @return string base64 representation of the image
    */
    public static function logo($white = false, $small = false)
    {
        if($white === true)
        {
            if($small) {
                return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAADIAAAAyABS+OT9AAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAMmSURBVEiJ7VRLiBxVFD33VdeUwe5MYsJM1esGHcVNBHGli7hIQFwI7kQZhySGieI2iEoIQVwoIbhS/CEqiAZ3Y8AouPEDRoIbDQ6oTewUVe8+WhSJjNLpqerjpmeo9EzPtEvBu3k87rnn3Hu47wH/xzYhWyU7nc4NURS9AuC2IAjmZ2dnu2u5NE13h2F4FsDvtVrtyZmZmZXNOMw4cpJhFEVLAI4B+LPf7zeq+ampqWkAfwBYKIri0yzLdkw+FwBVfV1Vqarvkdy0EZLinHt1iDs7Mbn3/oFh0VckQwBwzi2q6teqetl7f0FVHx+KGFU9r6p0zs2Pco2z6BCAVRE5LCKr3vt9IvIWAAugTzIB8Gae53eJyMAYcxTA3yJyaCIBkl8CuJQkyRXn3DzJCwBWAdRFZBpAneSqMeaLPM8Px3H8K4CLw7rrYuwWee9fJrkC4BmSJ6y1L4kIK02I9/64iJwh+aKItOI4XqxithQgWfPe/wjgmrX2jnE459x3xpidvV5v39zcXG80P3ZNRaQA8DOAHc65o6ObRNI45+aNMbtI/rQZ+dgJSIbe+0dIPkfyhSAI9pC8F8BVAAWAGoCdAL4F0BCRhwHclyTJlS0FSAaq+piInBSRz0WkVZblu81m88M1jKp+YK1dqNwXSD5ljGmQ/AbAKWttusEi59x+7/33IvIEgIeSJFkcDAYrInKdNSJyN8latS8R6RRFcSeAFMAl7/3ptfezDgzD8HJZlmfiOH5fRAZpmu4GcA3AAQDrr5Tkjd1uNxpaBRG5H0C/1Wr1ROSUqi6R3LueH/Ws3W5H9Xr9WZKxMeY1kkskzwE4ba39TVUvWmvvUdW9AE4AeJDk0yJyBMAb1trPqnwbtqjRaOwn+TyAj+I4/qEoigMk9wDIVTUHcPPwzERkV1mWB5vN5jkAfwF4Z5RvwwTDbzgF8LG19tGKNbU8z28JgmC6LMurrVarIyIlAGRZdlMQBB0A56s1Y0NVT6rqwHt/cFswAO/926ray7Ls9knwIBmq6ieq+ku32711G/IjqtpT1WMTkVdEZHl5eWoSbLvdjv4V+X8q/gELULH7n79XgQAAAABJRU5ErkJggg==';
            } else {
                return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAMAAADW3miqAAAAA3NCSVQICAjb4U/gAAAACXBIWXMAAASwAAAEsAHnLCemAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAAjpQTFRFAAAA////////////////////1dXV29vb39/f4+Pj6urq6+vr7e3t7u7u39/f4eHh4+Pj5OTk5+fn6enp4+Pj5OTk5eXl5ubm6Ojo6enp6enp4+Pj4+Pj5+fn6Ojo6enp5OTk5OTk5eXl5ubm5ubm5ubm5+fn6Ojo5eXl5eXl5ubm5+fn5+fn6Ojo5OTk5eXl5ubm5ubm5ubm5+fn5eXl5ubm5ubm5OTk5+fn5+fn5ubm5ubm5ubm5+fn5+fn5+fn5eXl5eXl5ubm5ubm5ubm5OTk5+fn5+fn5eXl5ubm5ubm5ubm5ubm5+fn5+fn5+fn5eXl5eXl5ubm5ubm5+fn5+fn5+fn5eXl5ubm5ubm6Ojo5ubm5OTk5ubm5eXl5ubm5+fn5+fn5eXl5+fn5ubm5ubm5ubm5ubm5eXl5eXl5ubm5ubm5ubm5eXl5ubm5eXl5ubm5+fn5eXl5+fn5ubm5ubm6Ojo5ubm5ubm5+fn5eXl5ubm5eXl5ubm5eXl5eXl5eXl5ubm5ubm5ubm5ubm5ubm5+fn5+fn5ubm5ubm5ubm5ubm5ubm5ubm5+fn5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5+fn5ubm5ubm5ubm5+fn5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5eXl5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5ubm5+fn5ubm5+fn5ubm5ubm5ubm5ubmTc25WgAAAL10Uk5TAAECAwQFBgcICQwNDg8QERITFRcbHB0eISIjJCUqLS4vMDEyMzQ1Nzo7PkBBQkNERkdISU1RUlRUVVtcXV5fYGFjZGVnaGlrbW5vcHFzdHV2d3h8fX5/gIKDg4SGhoeHiImKioyNjpGUlZeYmZqam5yen5+ho6Olpqepra+vsLKztLW2uLq8vb6/wMHDxMbIysvMzc7P0NLT1NXV19na29ze3+Dk5ujp6uvs7O3u7/Lz9fb3+Pn6+vv7/P3+9QmFKQAAAfRJREFUGBnNwQlDSwEAB/D/e9vb1oZKaWGiEHLknJCyOXIfwxhSct9y3zG3LFSmiJguaqZWOfb+381bzext+wD9fhjpxAWZiCiwILn0x+ycjjChgoNlSCbNSzYskaAwO4KkA4l0z8g7KYjI76ZcjAQnSbcWUXkB9k9DnMWk1wiM3vag0XNhDoBFIX7SQ+0NAxbA5OOX2tdBbgCwl1wDlVSyAsBS9nX5e77JrQBSOngdKkI9cyEcDpKUZZLvc4FztEMtq61EbGLbunQAmqk3QqHlmrrTiJfXWUO3iIjJA7/PP9QigfWnrEHUWfpSkUgTYgGibvMAkjDwg79YhyHjLvezHIn0a7mvxNN9cXfp+sqn/uocXkW8Udvbe+kCspdtunts9XwDJPKlVUQM0y5/8KiZLoTdskEh8ZKbTUUC/lnRzptmGOhCWN1OKCSWY24zX0zBsBkd360AhN4zCPt4HIpMbgX0VbLHiGH6LACSzft1LBQtR6DYT6cGwETE0NkbHbMH68cDqN0BiJv/3HM2rNRC5TlLgaK+4DXbwicHrXta+MiIMt6HShe3AJhQ/YNDvKtEwMnPULnCdyIU2px5hYWzMqCQfDwFlUm/aIPaRg5kQ+0Ee8yIZQnwEOIY37I5H1HCzFa+MiBeRlWJCf+NsVemYQT7Cw6fowCCWHYIAAAAAElFTkSuQmCC';
            }
        }
        if($small) {
            return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAADEwAAAxMBPWaDxwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAHcSURBVEiJ7dTNi81hFAfwz71zryHCZULNgmQ2kkTZWLGwsLGRlxTJyx+grJSFhSzYTKFokJexIhtkIy+ZmpRQkrdrlCQ7IjKasTjP5bqvv8tK+W5Oz3O+zznPOc/3OfzHX2IiTuAGZtf4SriGc5jyJ8GLKcA4LmNBjX8eBpP/DiZ1muBYOnwG+SacHI4k3mAnwdekQ7dFJbAdd/ESQ9iZ9vO4kvibsia4gG+iDbAQY3iFJ8mOYUnyz8JnXK0N1Kz0W3iEkXSrIYyKx5yW7ChuYgveYzidy4x+HMB37BH9rkYOu5N/P0424LREAS/wuA3vAcpC0nVo1iLpZs+E/LY14OZF+6bjKb42u2UjFLEBfaL8mbiIDylxAVNxT8hzvRDESIsLgy4hxzIGcB0bazjna9abcR/PxZ+ZW+2sLnsFHmIX1qVEn9S3ZrnfKx8Xsl2M10J9B/36Pz8xR0iuErAkyj9ew3uLyVXr0+LfVBS0FKu1UFQ39uEoFonSD6En+YeT7cFhIYS1uJQCt8UqUXaF3ItTQiVv8C7ZL+KdehPvbNpvixI+qh9eBTFRlyXbVeWbIRSWeeDtFbNmZUb+gKiwL2uCohhcZcxvw92agu/IGryCHCZk5HZ3GvzfwQ+Y92XJiBD6DQAAAABJRU5ErkJggg==';
        } else {
            return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAEsAAABLAB5ywnpgAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAANJSURBVFiF7ddZqFVlFAfw31HvIFhRET1YQYMvQYONkBA0EPXQQ3J7KsuokBDKaMCHwqhIigaCopEioqSgsrKBKEoKadSLGVJJRknULROUut4bnt3D+g57390+5559jr3dPxzOZn1rre//7TV8azODGfSH2T3YzMIF2Is90+iejUH82cM+XeFQvI8Mv+G0NnoNrE56k1j+f5A5BKNpkwxf4RIMVOgehZUYL+iv3J9kBvFhwflrmNuF3en4I9k0sWR/EXq0QOYtzKlhuxC7k+1enNovmYsLZEYxr7B2EG7B29iIT/A0zin5uAj7ko9tGOqH0KbkaDcWFOQHYHta+xHr8YU8b64v+bmzcLBlvZI5uOBkdWltJMn/xpgo7Z0iZ5r4vqQ/F78mmxd7JdTAl8nJyQXZfaZWUCtpmyXZNwU7eDLJr+mVEByJHbhSNMWvk9MduE70piJm4xSsEXmzD5cl+QY83g+ZFhaKRviGvNJmdWF3IibwD57Cu+pVaEcsFl23qd6V0wrTdpGTHdHNKVt4PRFpYFENu8PS/xrsqmE3LYbFSb8VFbVEdPB2OALPiWaY4Z5uNuk2nkNYmp5fFs1tGR7CmyIcP4neNB9niOpaK66PzaI4+saBuBW/4C9x0rsK60fjUqxImz+Ia8V4Mpx0BuRt4FORi3VSBXHS20VoxvGAuMHLhIp4BVdXyFuEnhXVmYnWMSLycVpcLt5IhpcSEfIcakdoA27rQKiVQ+diS5J9jJM6kTlTtPdd4tUW0RBhe6KN7Xd4uEI+P21+c0E2JLp9U1zI8yrspiiXE3BAhGMUv8tLuYituL9CfncitMp/+9dxZeWq5JrAz+l5UNw7nyUSK8TI8Q6OKdmNJbJF3zeKSXFdkn2OK+TVva1i/474SJxuaUE2Im74cbwg3tyF+EBMBItxh3hjGd6Th2R5kq3TI8aSg5tK8mPxjPjqyNr8RnGVqVFYldZ+6JXQ8/Ixoiq8c3ACzsP56bcIh1foDsgHusd6JXS8uKkz1T2mDm5IfiZEQ+0ZjyRHO+U9qS4WyAf9e/shQyTk5uRsi7ibukUDZ4lxNhNVNtzJoJu5ZhKvJodrRXedrEFqj2i0W8WEOd3n9wxm0Bf+BTTT57TH6bn3AAAAAElFTkSuQmCC';
        }
    }

}
