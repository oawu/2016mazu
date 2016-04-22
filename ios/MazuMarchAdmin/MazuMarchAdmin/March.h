//
//  March.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface March : NSObject

@property NSString *marchId, *title;
@property double battery;
@property BOOL isEnable;

- (March *) initWithDictionary:(NSDictionary *)dictionary;


@end
